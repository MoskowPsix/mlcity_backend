<?php

namespace App\MoonShine\Pages\MoonPlace;

use App\MoonShine\Resources\MoonSeanceResource;
use MoonShine\Fields\Relationships\BelongsTo;
use MoonShine\Fields\Relationships\HasMany;

trait MoonPlaceHelperPageTrait
{
    public function showSeances(): HasMany
    {
        return HasMany::make('Сеансы', 'seances', resource: new MoonSeanceResource())
            ->searchable(false);
    }
}
