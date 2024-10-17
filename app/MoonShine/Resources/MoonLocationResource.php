<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\Location;
use Illuminate\Database\Eloquent\Model;
use App\MoonShine\Pages\MoonLocation\MoonLocationIndexPage;
use App\MoonShine\Pages\MoonLocation\MoonLocationFormPage;
use App\MoonShine\Pages\MoonLocation\MoonLocationDetailPage;

use MoonShine\Resources\ModelResource;
use MoonShine\Pages\Page;

/**
 * @extends ModelResource<Location>
 */
class MoonLocationResource extends ModelResource
{
    protected string $model = Location::class;

    protected string $title = 'Города';

    /**
     * @return list<Page>
     */
    public function pages(): array
    {
        return [
            MoonLocationIndexPage::make($this->title()),
            MoonLocationFormPage::make(
                $this->getItemID()
                    ? __('moonshine::ui.edit')
                    : __('moonshine::ui.add')
            ),
            MoonLocationDetailPage::make(__('moonshine::ui.show')),
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
