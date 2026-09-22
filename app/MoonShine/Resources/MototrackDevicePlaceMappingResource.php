<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\MototrackDevicePlaceMapping;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;
use MoonShine\Decorations\Block;
use MoonShine\Fields\ID;
use MoonShine\Fields\Relationships\BelongsTo;
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
        ];
    }

    public function indexFields(): array
    {
        return [
            ID::make()->sortable(),
            Text::make('ID устройства', 'device_id')->sortable(),
            Text::make('Название', 'name')->sortable(),
            BelongsTo::make('Место проведения', 'place', resource: new PlaceResource()),
        ];
    }

    public function formFields(): array
    {
        return [
            Block::make([
                ID::make(),
                Text::make('ID устройства', 'device_id')
                    ->required()
                    ->hint('Значение device_id из backend-а ESP/Mega.'),
                Text::make('Название', 'name'),
                BelongsTo::make('Место проведения', 'place', resource: new PlaceResource())
                    ->required()
                    ->asyncSearch(),
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
            BelongsTo::make('Место проведения', 'place', resource: new PlaceResource()),
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
            'place_id' => ['required', 'integer', 'exists:places,id'],
            'name' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
