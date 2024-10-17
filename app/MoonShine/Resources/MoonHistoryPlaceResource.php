<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\HistoryPlace;
use Illuminate\Database\Eloquent\Model;
use App\MoonShine\Pages\MoonHistoryPlace\MoonHistoryPlaceIndexPage;
use App\MoonShine\Pages\MoonHistoryPlace\MoonHistoryPlaceFormPage;
use App\MoonShine\Pages\MoonHistoryPlace\MoonHistoryPlaceDetailPage;

use MoonShine\Resources\ModelResource;
use MoonShine\Pages\Page;

/**
 * @extends ModelResource<HistoryPlace>
 */
class MoonHistoryPlaceResource extends ModelResource
{
    protected string $model = HistoryPlace::class;

    protected string $title = 'История мест ведения';

    /**
     * @return list<Page>
     */
    public function pages(): array
    {
        return [
            MoonHistoryPlaceIndexPage::make($this->title()),
            MoonHistoryPlaceFormPage::make(
                $this->getItemID()
                    ? __('moonshine::ui.edit')
                    : __('moonshine::ui.add')
            ),
            MoonHistoryPlaceDetailPage::make(__('moonshine::ui.show')),
        ];
    }

    /**
     * @param HistoryPlace $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    public function rules(Model $item): array
    {
        return [];
    }
}
