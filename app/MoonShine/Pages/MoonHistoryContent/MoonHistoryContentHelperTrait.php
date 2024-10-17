<?php

namespace App\MoonShine\Pages\MoonHistoryContent;

use App\MoonShine\Resources\MoonHistoryPlaceResource;
use App\MoonShine\Resources\MoonPlaceResource;
use App\MoonShine\Resources\MoonPriceResource;
use MoonShine\Fields\Relationships\BelongsToMany;
use MoonShine\Fields\Relationships\HasMany;

trait MoonHistoryContentHelperTrait
{
    public function showHistoryPlace()
    {
        return HasMany::make('Изменённые Места проведения', 'historyPlaces', resource: new MoonHistoryPlaceResource());
    }
}
