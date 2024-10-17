<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\Place;
use Illuminate\Database\Eloquent\Model;
use App\MoonShine\Pages\MoonPlace\MoonPlaceIndexPage;
use App\MoonShine\Pages\MoonPlace\MoonPlaceFormPage;
use App\MoonShine\Pages\MoonPlace\MoonPlaceDetailPage;

use MoonShine\Resources\ModelResource;
use MoonShine\Pages\Page;

/**
 * @extends ModelResource<Place>
 */
class MoonPlaceResource extends ModelResource
{
    protected string $model = Place::class;

    protected string $title = 'Места проведения';
    protected bool $detailInModal = true;

    /**
     * @return list<Page>
     */
    public function pages(): array
    {
        return [
            MoonPlaceIndexPage::make($this->title()),
            MoonPlaceFormPage::make(
                $this->getItemID()
                    ? __('moonshine::ui.edit')
                    : __('moonshine::ui.add')
            ),
            MoonPlaceDetailPage::make(__('moonshine::ui.show')),
        ];
    }

    /**
     * @param Place $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    public function rules(Model $item): array
    {
        return [];
    }
}
