<?php

namespace App\Services;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Reader\CSV\Options as CsvOptions;
use OpenSpout\Reader\CSV\Reader as CsvReader;
use OpenSpout\Reader\XLSX\Reader as XlsxReader;
use OpenSpout\Writer\XLSX\Writer as XlsxWriter;

class CheckpointImportService
{
    public const FIELDS = ['last_name', 'first_name', 'middle_name', 'birth_date', 'city', 'group_name', 'start_number', 'rfid'];

    public const LABELS = [
        'last_name' => 'Фамилия',
        'first_name' => 'Имя',
        'middle_name' => 'Отчество',
        'birth_date' => 'Дата рождения',
        'city' => 'Город',
        'group_name' => 'Группа',
        'start_number' => 'Стартовый номер',
        'rfid' => 'RFID',
    ];

    public function prepare(Request $request, Event $event): array
    {
        $request->validate(['file' => 'required|file|max:10240|mimes:csv,txt,xlsx', 'mapping' => 'nullable|array']);
        $file = $request->file('file');
        $reader = strtolower($file->getClientOriginalExtension()) === 'xlsx'
            ? new XlsxReader()
            : new CsvReader(tap(new CsvOptions(), function (CsvOptions $options) use ($file) {
                $options->FIELD_DELIMITER = $this->delimiter($file->getRealPath());
            }));
        try {
            $reader->open($file->getRealPath());
            $rawRows = [];
            foreach ($reader->getSheetIterator() as $sheet) {
                foreach ($sheet->getRowIterator() as $row) {
                    $rawRows[] = array_map(function ($cell) {
                        $value = $cell->getValue();
                        return $value instanceof \DateTimeInterface ? $value->format('Y-m-d') : trim((string) $value);
                    }, $row->getCells());
                    if (count($rawRows) > 5001) {
                        throw ValidationException::withMessages(['file' => 'Максимум 5000 участников в одном файле.']);
                    }
                }
                break;
            }
        } finally {
            $reader->close();
        }
        if (!$rawRows) {
            throw ValidationException::withMessages(['file' => 'Файл пуст.']);
        }
        $headers = array_map(fn ($value) => trim($value, " \t\n\r\0\x0B\xEF\xBB\xBF"), array_shift($rawRows));
        $mapping = $this->mapping($request, $headers);
        if (!in_array('last_name', $mapping, true) || !in_array('first_name', $mapping, true) || !in_array('start_number', $mapping, true)) {
            return ['headers' => $headers, 'mapping_required' => true, 'fields' => self::FIELDS, 'rows' => [], 'errors' => []];
        }
        if (count(array_unique(array_values($mapping))) !== count($mapping) || array_diff(array_values($mapping), self::FIELDS) || array_diff(array_keys($mapping), $headers)) {
            throw ValidationException::withMessages(['mapping' => 'Некорректное сопоставление колонок.']);
        }
        $rows = [];
        $errors = [];
        $numbers = [];
        foreach ($rawRows as $index => $values) {
            if (!array_filter($values, fn ($value) => $value !== '')) continue;
            $row = array_fill_keys(self::FIELDS, null);
            foreach ($headers as $column => $header) {
                if (isset($mapping[$header])) {
                    $row[$mapping[$header]] = ($values[$column] ?? '') !== '' ? ($values[$column] ?? '') : null;
                }
            }
            $line = $index + 2;
            $validator = Validator::make($row, [
                'last_name' => 'required|string|max:255', 'first_name' => 'required|string|max:255',
                'middle_name' => 'nullable|string|max:255', 'birth_date' => 'nullable|date_format:Y-m-d',
                'city' => 'nullable|string|max:255', 'group_name' => 'nullable|string|max:255',
                'start_number' => 'required|string|max:255', 'rfid' => 'nullable|string|max:255',
            ]);
            if ($validator->fails()) {
                $errors[] = ['row' => $line, 'message' => $validator->errors()->first()];
            }
            if ($row['start_number'] !== null) {
                if (isset($numbers[$row['start_number']])) {
                    $errors[] = ['row' => $line, 'message' => 'Стартовый номер повторяет строку '.$numbers[$row['start_number']]];
                }
                $numbers[$row['start_number']] = $line;
            }
            $rows[] = ['line' => $line] + $row;
        }
        if ($errors) {
            return ['headers' => $headers, 'mapping_required' => false, 'fields' => self::FIELDS, 'rows' => [], 'errors' => $errors];
        }
        return ['headers' => $headers, 'mapping_required' => false, 'fields' => self::FIELDS, 'rows' => $rows, 'errors' => []];
    }

    public function writeTemplate(string $path): void
    {
        $writer = new XlsxWriter();
        $writer->openToFile($path);
        $writer->addRow(Row::fromValues(array_values(self::LABELS)));
        $writer->addRow(Row::fromValues(['Иванов', 'Иван', 'Петрович', '1998-05-12', 'Казань', 'М18', '12', 'E200001122334455']));
        $writer->addRow(Row::fromValues(['Петрова', 'Анна', null, '2001-03-01', 'Набережные Челны', 'Ж18', '7', null]));
        $writer->close();
    }

    private function mapping(Request $request, array $headers): array
    {
        if (!$request->exists('mapping')) {
            $mapping = [];
            foreach ($headers as $header) {
                $field = $this->fieldByHeader($header);
                if ($field !== null) {
                    $mapping[$header] = $field;
                }
            }
            return $mapping;
        }
        $mapping = [];
        foreach ((array) $request->input('mapping') as $header => $field) {
            if (!is_string($field) || $field === '') {
                continue;
            }
            $mapping[(string) $header] = $field;
        }
        return $mapping;
    }

    public function commit(Event $event, array $rows): int
    {
        DB::transaction(function () use ($event, $rows) {
            $groups = $event->checkpointGroups()->pluck('id', 'name')->all();
            foreach ($rows as $row) {
                $groupId = null;
                if ($row['group_name']) {
                    if (!isset($groups[$row['group_name']])) {
                        $group = $event->checkpointGroups()->create(['name' => $row['group_name']]);
                        $groups[$row['group_name']] = $group->id;
                    }
                    $groupId = $groups[$row['group_name']];
                }
                $event->checkpointParticipants()->updateOrCreate(
                    ['start_number' => $row['start_number']],
                    ['last_name' => $row['last_name'], 'first_name' => $row['first_name'],
                        'middle_name' => $row['middle_name'], 'birth_date' => $row['birth_date'],
                        'city' => $row['city'], 'group_id' => $groupId, 'rfid' => $row['rfid']]
                );
            }
            $event->touch();
        });
        return count($rows);
    }

    private function fieldByHeader(string $header): ?string
    {
        if (in_array($header, self::FIELDS, true)) {
            return $header;
        }
        $normalized = mb_strtolower(trim($header));
        foreach (self::LABELS as $field => $label) {
            if ($normalized === mb_strtolower($label)) {
                return $field;
            }
        }
        return null;
    }

    private function delimiter(string $path): string
    {
        $first = (string) fgets(fopen($path, 'rb'));
        return substr_count($first, ';') > substr_count($first, ',') ? ';' : ',';
    }
}
