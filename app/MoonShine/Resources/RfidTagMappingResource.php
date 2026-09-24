<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\RfidTagMapping;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;
use MoonShine\Decorations\Block;
use MoonShine\Fields\ID;
use MoonShine\Fields\Text;
use MoonShine\Fields\Textarea;
use MoonShine\Resources\ModelResource;

/**
 * @extends ModelResource<RfidTagMapping>
 */
class RfidTagMappingResource extends ModelResource
{
    protected string $model = RfidTagMapping::class;

    protected string $title = 'RFID соответствия';

    protected string $column = 'moto_tag_number';

    protected bool $simplePaginate = true;

    protected bool $isAsync = true;

    public function search(): array
    {
        return [
            'id',
            'moto_tag_number',
            'sensor_value',
            'normalized_sensor_value',
            'source',
        ];
    }

    public function indexFields(): array
    {
        return [
            ID::make()->sortable(),
            Text::make('Номер датчика', 'moto_tag_number')->sortable(),
            Text::make('Значение датчика', 'sensor_value'),
            Text::make('Нормализованное значение', 'normalized_sensor_value'),
            Text::make('Источник', 'source')->sortable(),
        ];
    }

    public function formFields(): array
    {
        return [
            Block::make([
                ID::make(),
                Text::make('Номер датчика', 'moto_tag_number')
                    ->required()
                    ->hint('Номер, который пользователь указывает в личном кабинете.'),
                Text::make('Значение датчика', 'sensor_value')
                    ->required()
                    ->hint('Сырое значение от ESP/Mega: длинный EPC/UID/строка метки.'),
                Text::make('Источник', 'source'),
                Textarea::make('Заметки', 'notes'),
            ]),
        ];
    }

    public function detailFields(): array
    {
        return [
            ID::make(),
            Text::make('Номер датчика', 'moto_tag_number'),
            Text::make('Значение датчика', 'sensor_value'),
            Text::make('Нормализованное значение', 'normalized_sensor_value'),
            Text::make('Источник', 'source'),
            Textarea::make('Заметки', 'notes'),
        ];
    }

    /**
     * @param RfidTagMapping $item
     *
     * @return array<string, string[]|string>
     */
    public function rules(Model $item): array
    {
        return [
            'moto_tag_number' => [
                'required',
                'string',
                'max:255',
                Rule::unique('rfid_tag_mappings', 'moto_tag_number')->ignore($item->getKey()),
            ],
            'sensor_value' => ['required', 'string'],
            'source' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
