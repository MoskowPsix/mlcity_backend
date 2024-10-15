<?php

declare(strict_types=1);

namespace App\MoonShine\Pages\MoonEvent;

use App\MoonShine\Resources\MoonStatusResource;
use App\MoonShine\Resources\MoonUserResource;
use MoonShine\Components\Card;
use MoonShine\Components\Carousel;
use MoonShine\Components\Layout\Div;
use MoonShine\Components\Link;
use MoonShine\Decorations\Block;
use MoonShine\Decorations\Column;
use MoonShine\Decorations\Grid;
use MoonShine\Decorations\TextBlock;
use MoonShine\Fields\Date;
use MoonShine\Fields\ID;
use MoonShine\Fields\Image;
use MoonShine\Fields\Relationships\BelongsTo;
use MoonShine\Fields\Relationships\BelongsToMany;
use MoonShine\Fields\Text;
use MoonShine\Pages\Crud\DetailPage;
use MoonShine\Components\MoonShineComponent;
use MoonShine\Fields\Field;
use Throwable;

class MoonEventDetailPage extends DetailPage
{
    use MoonEventPageTrait;
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
                $this->showPrices(),
        ];
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
        $cards = [];
        foreach($this->getResource()->getItem()->prices as $price) {
            $cards[] = Column::make([Card::make(
                    title: 'Билет',
                    values: [
                        'Цена' => $price->cost_rub . ' р.',
                    ],
                    subtitle: $price->description
                )
            ])->columnSpan(3);
        }
        return [
            Grid::make('Цена',$cards)->customAttributes(['class' => 'mt-8']),
            ...parent::bottomLayer()
        ];
    }
}
