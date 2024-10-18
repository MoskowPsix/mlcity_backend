<?php

declare(strict_types=1);

namespace App\MoonShine\Pages\Sight;

use App\MoonShine\Pages\Event\EventHelperPageTrait;
use App\MoonShine\Resources\MoonUserResource;
use GianTiaga\MoonshineCoordinates\Dto\CoordinatesDto;
use GianTiaga\MoonshineCoordinates\Fields\Coordinates;
use MoonShine\Components\Carousel;
use MoonShine\Components\Layout\Div;
use MoonShine\Components\Link;
use MoonShine\Fields\Date;
use MoonShine\Fields\ID;
use MoonShine\Fields\Number;
use MoonShine\Fields\Relationships\BelongsTo;
use MoonShine\Fields\Text;
use MoonShine\Pages\Crud\DetailPage;
use MoonShine\Components\MoonShineComponent;
use MoonShine\Fields\Field;
use Throwable;

class SightDetailPage extends DetailPage
{
    use EventHelperPageTrait;
    /**
     * @return list<MoonShineComponent|Field>
     */
    public function fields(): array
    {
        return [
            $this->showLastStatus(),
            $this->showCountLikes(),
            $this->showCountFavorites(),
            ID::make(),
            Text::make('Название', 'name'),
            BelongsTo::make('Автор', 'author', resource: new MoonUserResource())
                ->changePreview(function ($data) {
                    return Link::make((new MoonUserResource())->detailPageUrl($data), $data->name);
                }),
            Text::make('Организатор', 'sponsor'),
            Number::make('Долгота', 'latitude'),
            Number::make('Широта', 'longitude'),
            Text::make('Адрес', 'address')
        ];
    }
    private function center()
    {
        return [55, 55];
    }
    /**
     * @return list<MoonShineComponent>
     * @throws Throwable
     */
    protected function topLayer(): array
    {
        return [
            Div::make([
                Carousel::make(
                    items: collect($this->getResource()->getItem()->files)->pluck('link')->all(),
                    portrait: false,
                )
            ]),
            ...parent::topLayer()
        ];
    }

    /**
     * @return list<MoonShineComponent>
     * @throws Throwable
     */
    protected function mainLayer(): array
    {
        return [
            ...parent::mainLayer()
        ];
    }

    /**
     * @return list<MoonShineComponent>
     * @throws Throwable
     */
    protected function bottomLayer(): array
    {
        return [
            ...parent::bottomLayer()
        ];
    }
}
