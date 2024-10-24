<?php

namespace App\MoonShine\Pages\HistoryContent;

use App\MoonShine\Resources\HistoryPlaceResource;
use App\MoonShine\Resources\PlaceResource;
use App\MoonShine\Resources\PriceResource;
use MoonShine\Fields\Relationships\BelongsToMany;
use MoonShine\Fields\Relationships\HasMany;

trait HistoryContentHelperTrait
{
    public function showHistoryPlace()
    {
        return HasMany::make('Изменённые Места проведения', 'historyPlaces', resource: new HistoryPlaceResource());
    }
}
