<?php

declare(strict_types=1);

namespace App\MoonShine\Pages\Event;

use App\MoonShine\Pages\HistoryPlace\HistoryPlaceIndexPage;
use App\MoonShine\Resources\HistoryContentResource;
use App\MoonShine\Resources\HistoryPlaceResource;
use App\MoonShine\Resources\MoonUserResource;
use Illuminate\Database\Eloquent\Model;
use MoonShine\Components\ActionGroup;
use MoonShine\Components\Card;
use MoonShine\Components\CardsBuilder;
use MoonShine\Components\Carousel;
use MoonShine\Components\Layout\Div;
use MoonShine\Components\Link;
use MoonShine\Decorations\Block;
use MoonShine\Decorations\Column;
use MoonShine\Decorations\Fragment;
use MoonShine\Decorations\Grid;
use MoonShine\Decorations\LineBreak;
use MoonShine\Fields\Date;
use MoonShine\Fields\ID;
use MoonShine\Fields\Markdown;
use MoonShine\Fields\Relationships\BelongsTo;
use MoonShine\Fields\Relationships\ModelRelationField;
use MoonShine\Fields\Text;
use MoonShine\Pages\Crud\DetailPage;
use MoonShine\Components\MoonShineComponent;
use MoonShine\Fields\Field;
use Throwable;

class EventDetailPage extends DetailPage
{
    use EventHelperPageTrait;
    /**
     * @return list<MoonShineComponent|Field>
     */
    public function fields(): array
    {
        return [
            ID::make(),
            Text::make('Название', 'name'),
            BelongsTo::make('Автор', 'author', resource: new MoonUserResource())
                ->changePreview(function ($data) {
                    return Link::make((new MoonUserResource())->detailPageUrl($data), $data->name);
                }),
            Text::make('Организатор', 'sponsor'),
            Date::make('Начало', 'date_start')->format('d.m.Y H:i'),
            Date::make('Конец', 'date_start')->format('d.m.Y H:i'),
            Markdown::make('Описание', 'description'),
            $this->showLastStatus(),
            $this->showCountLikes(),
            $this->showCountFavorites(),
            $this->showPlaces(),
            $this->showOrganization(),
        ];
    }
    /**
     * @return list<MoonShineComponent>
     * @throws Throwable
     */
    protected function topLayer(): array
    {
        if ($this->getCurrentStatus()->name == 'Изменено'){
            return [
                Grid::make([
                    Column::make([

                    ])->columnSpan(6),
                    Column::make([

                    ])->columnSpan(6),
                ]),
                ...parent::topLayer()
            ];
        } else {
            return [
                Div::make('Билеты', [
                    Carousel::make(
                        items: collect($this->getResource()->getItem()->files)->pluck('link')->all(),
                        portrait: false,
                    )
                ]),
                ...parent::topLayer()
            ];
        }
    }
    private function getCurrentStatus(): Model
    {
        $statuses = $this->getResource()->getItem()->statuses;
        $result = '';
        foreach ($statuses as $status) {
            if($status->pivot->last) {
                $result = $status;
                break;
            }
        }
        return $result;
    }
    /**
     * @return list<MoonShineComponent>
     * @throws Throwable
     */
    protected function mainLayer(): array
    {
        if ($this->getCurrentStatus()->name == 'Изменено') {
            return $this->showForDetailChange();
        } else {
            return [
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
        if ($this->getCurrentStatus()->name == 'Изменено') {
            return [
                Grid::make([
                    Column::make([
                        ...parent::bottomLayer(),
                    ])->columnSpan(6),
                    Column::make([
                        ...$this->showBottonForChange(),
                    ])->columnSpan(6),
                ]),
            ];
        } else {
            return [
                $this->showGridCardPriceUI($this->getResource()->getItem()->prices),
                ...parent::bottomLayer(),
                $this->showActionStatusButton(),
            ];
        }
    }
    private function showForDetailChange(): array
    {
        $resource = $this->getResource();
        $history_resource = (new HistoryContentResource());
        $item = $resource->getItem();
        $history_item = $item->historyContents()->orderBy('created_at', 'DESC')->first();
        return [
            Grid::make([
                Column::make([
                    Block::make('Оригинал',[
                        Div::make('Билеты', [
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
                        Div::make('Билеты', [
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
                LineBreak::make(),

                ActionGroup::make($resource->getDetailItemButtons())
                    ->setItem($item)
                    ->customAttributes(['class' => 'justify-end']),
            ]),
        ];
    }

    public function showBottonForChange(): array
    {
        $components = [];
        $item = $this->getResource()->getItem()->historyContents()->first();

        if (! $item?->exists) {
            return $components;
        }
        $outsideFields = (new HistoryContentResource())->getDetailFields(onlyOutside: true);
        if ($outsideFields->isNotEmpty()) {
            $components[] = LineBreak::make();

            /** @var ModelRelationField $field */
            foreach ($outsideFields as $field) {
                $field->resolveFill(
                    $item?->attributesToArray() ?? [],
                    $item
                );

                $components[] = LineBreak::make();
                $index_fields = (new HistoryPlaceResource())->getIndexFields();
//                dd($field->);
//                CardsBuilder::make($field->value())->fields($index_fields);
//                $card = Card::make(values: $field->getResource()->getItem()->get()->toArray());
                $blocks = [$field];

                if ($field->toOne()) {
                    $field
                        ->withoutWrapper()
                        ->forcePreview();

                    $blocks = [
                        Block::make($field->label(), [$field]),
                    ];
                }

                $components[] = Fragment::make($blocks)
                    ->name($field->getRelationName());
            }
        }
        return array_merge($components, (new HistoryContentResource())->getDetailPageComponents());
    }
}
