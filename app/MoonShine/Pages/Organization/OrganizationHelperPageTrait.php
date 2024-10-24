<?php

namespace App\MoonShine\Pages\Organization;

use MoonShine\Fields\Relationships\BelongsTo;

trait OrganizationHelperPageTrait
{
    function showSight(): BelongsTo
    {
//       return BelongsTo::make('Место', 'sight', resource: );
    }
}
