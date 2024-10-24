<?php

namespace App\MoonShine\Pages\Location;

use App\MoonShine\Resources\LocationResource;
use MoonShine\Fields\Relationships\BelongsTo;

trait LocationHelperPageTrait
{
    public function showParentLocation(): BelongsTo
    {
        return BelongsTo::make('Родительская локация', 'locationParent', resource: new LocationResource());
    }
}
