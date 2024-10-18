<?php

namespace App\MoonShine\Pages\HistoryPlace;

use App\MoonShine\Resources\HistorySeanceResource;
use App\MoonShine\Resources\SeanceResource;
use MoonShine\Fields\Relationships\HasMany;

trait HistoryPlaceHelperTrait
{

    public function showSeances()
    {
        return HasMany::make('Сеансы', 'historySeances', resource: new HistorySeanceResource())
            ->searchable(false);
    }
}
