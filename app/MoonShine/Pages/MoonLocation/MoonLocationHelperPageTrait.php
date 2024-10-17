<?php

namespace App\MoonShine\Pages\MoonLocation;

use App\MoonShine\Resources\MoonLocationResource;
use MoonShine\Fields\Relationships\BelongsTo;

trait MoonLocationHelperPageTrait
{
    public function showParentLocation(): BelongsTo
    {
        return BelongsTo::make('Родительская локация', 'locationParent', resource: new MoonLocationResource());
    }
}
