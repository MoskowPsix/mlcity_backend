<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\Location;
use Illuminate\Database\Eloquent\Model;
use App\MoonShine\Pages\Location\LocationIndexPage;
use App\MoonShine\Pages\Location\LocationFormPage;
use App\MoonShine\Pages\Location\LocationDetailPage;

use MoonShine\Resources\ModelResource;
use MoonShine\Pages\Page;

/**
 * @extends ModelResource<Location>
 */
class LocationResource extends ModelResource
{
    protected string $model = Location::class;

    protected string $title = 'Города';

    /**
     * @return list<Page>
     */
    public function pages(): array
    {
        return [
            LocationIndexPage::make($this->title()),
            LocationFormPage::make(
                $this->getItemID()
                    ? __('moonshine::ui.edit')
                    : __('moonshine::ui.add')
            ),
            LocationDetailPage::make(__('moonshine::ui.show')),
        ];
    }

    /**
     * @param Location $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    public function rules(Model $item): array
    {
        return [];
    }
}
