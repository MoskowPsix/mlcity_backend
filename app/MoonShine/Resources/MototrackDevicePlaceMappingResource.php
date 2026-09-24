<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\MototrackDevicePlaceMapping;
use App\Models\Sight;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;
use MoonShine\Decorations\Block;
use MoonShine\Fields\ID;
use MoonShine\Fields\Text;
use MoonShine\Fields\Textarea;
use MoonShine\Resources\ModelResource;

/**
 * @extends ModelResource<MototrackDevicePlaceMapping>
 */
class MototrackDevicePlaceMappingResource extends ModelResource
{
    protected string $model = MototrackDevicePlaceMapping::class;

    protected string $title = 'RFID антенны';

    protected string $column = 'device_id';

    protected bool $simplePaginate = true;

    protected bool $isAsync = true;

    public function search(): array
    {
        return [
            'id',
            'device_id',
            'name',
            'sight_id',
        ];
    }

    public function indexFields(): array
    {
        return [
            ID::make()->sortable(),
            Text::make('ID устройства', 'device_id')->sortable(),
            Text::make('Название', 'name')->sortable(),
            Text::make('sight_id', 'sight_id')->sortable(),
            Text::make('Место', 'sight.name'),
        ];
    }

    public function formFields(): array
    {
        $exampleIds = Sight::query()
            ->orderBy('id')
            ->limit(10)
            ->pluck('id')
            ->implode(', ');

        return [
            Block::make([
                ID::make(),
                Text::make('ID устройства', 'device_id')
                    ->required()
                    ->hint('Значение device_id из backend-а ESP/Mega.'),
                Text::make('Название', 'name'),
                Text::make('sight_id', 'sight_id')
                    ->required()
                    ->hint(
                        'ID из раздела «Места» (таблица sights).'
                        .($exampleIds !== '' ? " Сейчас есть: {$exampleIds}." : '')
                    ),
                Textarea::make('Заметки', 'notes'),
            ]),
        ];
    }

    public function detailFields(): array
    {
        return [
            ID::make(),
            Text::make('ID устройства', 'device_id'),
            Text::make('Название', 'name'),
            Text::make('sight_id', 'sight_id'),
            Text::make('Место', 'sight.name'),
            Textarea::make('Заметки', 'notes'),
        ];
    }

    /**
     * @param MototrackDevicePlaceMapping $item
     *
     * @return array<string, string[]|string>
     */
    public function rules(Model $item): array
    {
        return [
            'device_id' => [
                'required',
                'string',
                'max:255',
                Rule::unique('mototrack_device_place_mappings', 'device_id')->ignore($item->getKey()),
            ],
            'sight_id' => [
                'required',
                'integer',
                'exists:sights,id',
            ],
            'name' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ];
    }

    public function validationMessages(): array
    {
        return [
            'sight_id.exists' => 'Место (sight) с таким id не найдено. Бери id из раздела «Места».',
            'sight_id.required' => 'Укажите sight_id.',
            'sight_id.integer' => 'sight_id должен быть числом.',
        ];
    }
}
