<?php

declare(strict_types=1);

namespace App\MoonShine\Pages\Sight;

use App\MoonShine\Pages\Event\EventHelperPageTrait;
use App\MoonShine\Resources\EventTypeResource;
use App\MoonShine\Resources\HistoryContentResource;
use App\MoonShine\Resources\LocationResource;
use App\MoonShine\Resources\MoonUserResource;
use App\MoonShine\Resources\SightTypeResource;
use MoonShine\Components\Carousel;
use MoonShine\Components\Layout\Div;
use MoonShine\Components\Link;
use MoonShine\Decorations\Block;
use MoonShine\Decorations\Column;
use MoonShine\Decorations\Fragment;
use MoonShine\Decorations\Grid;
use MoonShine\Fields\ID;
use MoonShine\Fields\Number;
use MoonShine\Fields\Relationships\BelongsTo;
use MoonShine\Fields\Relationships\BelongsToMany;
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
            BelongsTo::make('Город', 'locations', resource: new LocationResource())->searchable(),
            Text::make('Адрес', 'address'),
            BelongsToMany::make('Типы', 'types', resource: new SightTypeResource()),

        ];
    }
    /**
     * @return list<MoonShineComponent>
     * @throws Throwable
     */
    protected function topLayer(): array
    {
        return [
            ...parent::topLayer()
        ];
    }

    /**
     * @return list<MoonShineComponent>
     * @throws Throwable
     */
    protected function mainLayer(): array
    {
        if($this->getCurrentStatus()->name == 'Изменено') {
            return $this->showForDetailChange();
        } else {
            return [
                Div::make([
                    Carousel::make(
                        items: collect($this->getResource()->getItem()->files)->pluck('link')->all(),
                        portrait: false,
                    )
                ]),
                ...parent::mainLayer()
            ];
        }
    }

    /**
     * @return list<MoonShineComponent>
     * @throws Throwable
     */
    protected function bottomLayer(): array
    {
        return [
            $this->showActionStatusButtonForSight(),
            ...parent::bottomLayer()
        ];
    }
    private function showForDetailChange()
    {
        $resource = $this->getResource();
        $history_resource = (new HistoryContentResource());
        $item = $resource->getItem();
        $history_item = $item->historyContents()->orderBy('created_at', 'DESC')->first();
        return [
            Grid::make([
                Column::make([
                    Block::make('Оригинал',[
                        Div::make('Галерея', [
                            Carousel::make(
                                items: collect($this->getResource()->getItem()->files)->pluck('link')->all(),
                                portrait: false,
                            )
                        ]),
                        Fragment::make([
                            $this->getResource()->modifyDetailComponent(
                                $this->detailComponent($item, $resource->getDetailFields())
                            ),
                        ])->name('crud-detail'),
                    ]),
                ])->columnSpan(6),
                Column::make([
                    Block::make('Изменения', [
                        Div::make('Галерея', [
                            Carousel::make(
                                items: collect($history_item->historyFiles)->pluck('link')->all(),
                                portrait: false,
                            )
                        ]),
                        Fragment::make([
                            $history_resource->modifyDetailComponent(
                                $history_resource->detailPage()->detailComponent($history_item, $history_resource->getDetailFields())
                            ),
                        ])->name('crud-detail'),
                    ]),
                ])->columnSpan(6),
            ])
        ];
    }
}
