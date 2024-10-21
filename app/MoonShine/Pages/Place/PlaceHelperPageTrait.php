<?php

namespace App\MoonShine\Pages\Place;

use App\MoonShine\Resources\LocationResource;
use App\MoonShine\Resources\SeanceResource;
use App\MoonShine\Resources\SightResource;
use MoonShine\Components\Link;
use MoonShine\Fields\Number;
use MoonShine\Fields\Relationships\BelongsTo;
use MoonShine\Fields\Relationships\HasMany;
use MoonShine\Fields\Relationships\HasOne;

trait PlaceHelperPageTrait
{
    public function showSeances(): HasMany
    {
        return HasMany::make('Сеансы', 'seances', resource: new SeanceResource())
            ->searchable(false);
    }
    public function showLocation(): BelongsTo
    {
        return BelongsTo::make('Город', 'location', resource: new LocationResource());
    }
    public function showSight(): HasOne
    {
        return HasOne::make('Место', 'sight', resource: new SightResource());
    }
}
