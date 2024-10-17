<?php

declare(strict_types=1);

namespace App\MoonShine\Pages\MoonEvent;

use App\Models\Status;
use App\Models\User;
use App\MoonShine\Pages\MoonHistoryContent\MoonHistoryContentDetailPage;
use App\MoonShine\Resources\MoonHistoryContentResource;
use App\MoonShine\Resources\MoonHistoryPlaceResource;
use App\MoonShine\Resources\MoonPlaceResource;
use App\MoonShine\Resources\MoonStatusResource;
use App\MoonShine\Resources\MoonUserResource;
use Google\Service\Transcoder\TextInput;
use Illuminate\Database\Eloquent\Model;
use MoonShine\ActionButtons\ActionButton;
use MoonShine\Components\ActionGroup;
use MoonShine\Components\Card;
use MoonShine\Components\Carousel;
use MoonShine\Components\FormBuilder;
use MoonShine\Components\Layout\Div;
use MoonShine\Components\Link;
use MoonShine\Decorations\Block;
use MoonShine\Decorations\Collapse;
use MoonShine\Decorations\Column;
use MoonShine\Decorations\Fragment;
use MoonShine\Decorations\Grid;
use MoonShine\Decorations\LineBreak;
use MoonShine\Decorations\TextBlock;
use MoonShine\Fields\Date;
use MoonShine\Fields\ID;
use MoonShine\Fields\Image;
use MoonShine\Fields\Markdown;
use MoonShine\Fields\Relationships\BelongsTo;
use MoonShine\Fields\Relationships\BelongsToMany;
use MoonShine\Fields\Relationships\HasMany;
use MoonShine\Fields\Relationships\ModelRelationField;
use MoonShine\Fields\Select;
use MoonShine\Fields\Text;
use MoonShine\MoonShineUI;
use MoonShine\Pages\Crud\DetailPage;
use MoonShine\Components\MoonShineComponent;
use MoonShine\Fields\Field;
use Throwable;

class MoonEventDetailPage extends DetailPage
{
    use MoonEventHelperPageTrait;
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
            Date::make('Начало', 'date_start')->format('d.m.Y H:i'),
            Date::make('Конец', 'date_start')->format('d.m.Y H:i'),
            Markdown::make('Описание', 'description'),
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
            return [...parent::mainLayer()];
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
        $history_resource = new MoonHistoryContentResource();
        $item = $resource->getItem();

        return [
            Grid::make([
                Column::make([
                    Block::make([
                        Fragment::make([
                            $this->getResource()->modifyDetailComponent(
                                $this->detailComponent($item, $resource->getDetailFields())
                            ),
                        ])->name('crud-detail'),
                    ]),
                ])->columnSpan(6),
                Column::make([
                    Block::make([
                        Fragment::make([
                            $history_resource->modifyDetailComponent(
                                $this->detailComponent($item, $history_resource->getDetailFields())
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
//        $model_history = $this->getResource()->getItem()->historyContents()->first();
//        return [
//            $this->showLastStatus(),
//            ID::make('ID Изменения')->setValue($model_history->id),
//            Text::make('Название')->hideOnDetail(!isset($model_history->name))->setValue($model_history->name),
//            Text::make('Организатор')->hideOnDetail(!isset($model_history->sponsor))->setValue($model_history->sponsor),
//            Date::make('Начало')->hideOnDetail(!isset($model_history->date_start))->setValue($model_history->date_start)->format('d.m.Y H:i'),
//            Date::make('Конец')->hideOnDetail(!isset($model_history->date_start))->setValue($model_history->date_start)->format('d.m.Y H:i'),
//            Markdown::make('Описание')->hideOnDetail(!isset($model_history->description))->setValue($model_history->description),
//            HasMany::make('Места проведения', 'history_places', resource: new MoonHistoryPlaceResource())->setResource($model_history),
//            (new MoonHistoryPlaceResource())->detailPageUrl($model_history->history_places),
//        ];
    }

    public function showBottonForChange(): array
    {
        $components = [];
        $item = $this->getResource()->getItem()->historyContents()->first();

        if (! $item?->exists) {
            return $components;
        }

        $outsideFields = (new MoonHistoryContentResource())->getDetailFields(onlyOutside: true);
        if ($outsideFields->isNotEmpty()) {
            $components[] = LineBreak::make();

            /** @var ModelRelationField $field */
            foreach ($outsideFields as $field) {
                $field->resolveFill(
                    $item?->attributesToArray() ?? [],
                    $item
                );

                $components[] = LineBreak::make();

                $blocks = [
                    $field,
                ];

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
        return array_merge($components, (new MoonHistoryContentResource())->getDetailPageComponents());
    }
}
