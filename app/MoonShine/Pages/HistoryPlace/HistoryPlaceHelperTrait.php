<?php

namespace App\MoonShine\Pages\HistoryPlace;

use App\MoonShine\Resources\HistorySeanceResource;
use App\MoonShine\Resources\LocationResource;
use App\MoonShine\Resources\SeanceResource;
use MoonShine\Fields\Field;
use MoonShine\Fields\ID;
use MoonShine\Fields\Relationships\BelongsTo;
use MoonShine\Fields\Relationships\HasMany;

trait HistoryPlaceHelperTrait
{

    public function showSeances()
    {
        return HasMany::make('Сеансы', 'historySeances', resource: new HistorySeanceResource())
            ->searchable(false);
    }
    public function showLocation(): BelongsTo
    {
        return BelongsTo::make('Город', 'location', resource: new LocationResource());
    }
    public function showOriginId(): ID
    {
        return ID::make('ID оригинала', 'place_id')
            ->changePreview(function ($value, Field $field) {
                if (is_null($value)) {
                    return '<p style="color: greenyellow">Новое<p>';
                } else if($field->getData()->on_delete) {
                    return '<p style="color: red">Удаление | ' . $value . ' <p>';
                }
                return '<p style="color: dodgerblue">Изменено | ' . $value . ' <p>';
            });
    }
}
