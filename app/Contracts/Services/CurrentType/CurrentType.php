<?php

namespace App\Contracts\Services\CurrentType;

use App\Contracts\Services\CurrentType\CurrentTypeInterface;

class CurrentType implements CurrentTypeInterface
{
    private string $type_name;
    private array $types;
    private array $current_type = [];

    public function __construct(string $type_name)
    {
        $this->type_name = $type_name;
        $this->culturaRuValid();
        $this->readCsv();
        $this->search();
    }

    private function search(): void
    {
        foreach ($this->types as $columns) {
            foreach ($columns as $column) {
                if (count($column) >= 2) {
                    foreach ($column as $type) {
                        if ($type === $this->type_name)
                            $this->current_type = ['id' => $columns[0][0], 'current_name' => $columns[1][0], 'input_name' => $type];
                    }
                }
            }
        }
    }

    private function readCsv(): void
    {
        $file = fopen("app/Contracts/Services/CurrentType/types.csv", "r");
        $result = [];
        while (($data = fgetcsv($file)) !== false) {

            $index = 0;
            foreach ($data as $key => $i) {
                $data[$key] = explode('|', $i);
                $index = $index + 1;
            }
            $result[] = $data;
        }
        fclose($file);
        unset($result[0]);
        $this->types = $result;
    }

    public function getType(): array
    {
        return $this->current_type;
    }

    private function culturaRuValid(): void
    {
        $this->type_name = match ($this->type_name) {
            'vistavki'  => 'Выставки',
            'vstrechi'  => 'Встречи',
            'kino'      => 'Кино',
            'kontserti' => 'Концерты',
            'obuchenie' => 'Обучение',
            'prazdniki' => 'Праздники',
            'prochie'   => 'Прочие',
            'spektakli' => 'Спектакли',
            'ekskursii' => 'Экскурсии',
            default => $this->type_name,
        };
    }
}
