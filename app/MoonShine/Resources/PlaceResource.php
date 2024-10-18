<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\Place;
use Illuminate\Database\Eloquent\Model;
use App\MoonShine\Pages\Place\PlaceIndexPage;
use App\MoonShine\Pages\Place\PlaceFormPage;
use App\MoonShine\Pages\Place\PlaceDetailPage;

use MoonShine\Resources\ModelResource;
use MoonShine\Pages\Page;

/**
 * @extends ModelResource<Place>
 */
class PlaceResource extends ModelResource
{
    protected string $model = Place::class;

    protected string $title = 'Места проведения';
    protected bool $detailInModal = true;

    public static array $activeActions = [];


    public function getActiveActions(): array
    {
        if(auth()->id() === $this->getItem()?->author_id) {
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
            PlaceIndexPage::make($this->title()),
            PlaceFormPage::make(
                $this->getItemID()
                    ? __('moonshine::ui.edit')
                    : __('moonshine::ui.add')
            ),
            PlaceDetailPage::make(__('moonshine::ui.show')),
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
