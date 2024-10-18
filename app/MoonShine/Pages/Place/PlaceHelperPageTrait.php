<?php

namespace App\MoonShine\Pages\Place;

use App\MoonShine\Resources\SeanceResource;
use MoonShine\Fields\Relationships\BelongsTo;
use MoonShine\Fields\Relationships\HasMany;

trait PlaceHelperPageTrait
{
    public function showSeances(): HasMany
    {
        return HasMany::make('Сеансы', 'seances', resource: new SeanceResource())
            ->searchable(false);
    }
}
