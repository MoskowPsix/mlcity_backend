<?php

namespace App\MoonShine\Pages\MoonOrganization;

use MoonShine\Fields\Relationships\BelongsTo;

trait MoonOrganizationHelperPageTrait
{
    function showSight(): BelongsTo
    {
//       return BelongsTo::make('Место', 'sight', resource: );
    }
}
