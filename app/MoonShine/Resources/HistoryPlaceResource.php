<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\HistoryPlace;
use Illuminate\Database\Eloquent\Model;
use App\MoonShine\Pages\HistoryPlace\HistoryPlaceIndexPage;
use App\MoonShine\Pages\HistoryPlace\HistoryPlaceFormPage;
use App\MoonShine\Pages\HistoryPlace\HistoryPlaceDetailPage;

use MoonShine\Enums\ClickAction;
use MoonShine\Resources\ModelResource;
use MoonShine\Pages\Page;

/**
 * @extends ModelResource<HistoryPlace>
 */
class HistoryPlaceResource extends ModelResource
{
    protected string $model = HistoryPlace::class;

    protected string $title = 'История мест ведения';

    public static array $activeActions = ['view'];
    protected ?ClickAction $clickAction = ClickAction::DETAIL;


    public function getActiveActions(): array
    {
        if (auth()->id() === $this->getItem()?->author_id) {
            return static::$activeActions;
        }

        return static::$activeActions;
    }

    /**
     * @return list<Page>
     */
    public function pages(): array
    {
        return [
            HistoryPlaceIndexPage::make($this->title()),
            HistoryPlaceFormPage::make(
                $this->getItemID()
                    ? __('moonshine::ui.edit')
                    : __('moonshine::ui.add')
            ),
            HistoryPlaceDetailPage::make(__('moonshine::ui.show')),
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
