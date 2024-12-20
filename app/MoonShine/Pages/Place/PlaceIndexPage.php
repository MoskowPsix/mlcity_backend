<?php

declare(strict_types=1);

namespace App\MoonShine\Pages\Place;

use MoonShine\Components\CardsBuilder;
use MoonShine\Contracts\MoonShineRenderable;
use MoonShine\Decorations\Block;
use MoonShine\Decorations\Fragment;
use MoonShine\Fields\Fields;
use MoonShine\Fields\ID;
use MoonShine\Fields\Number;
use MoonShine\Fields\Text;
use MoonShine\Pages\Crud\IndexPage;
use MoonShine\Components\MoonShineComponent;
use MoonShine\Fields\Field;
use MoonShine\Decorations\Collapse;
use Throwable;

class PlaceIndexPage extends IndexPage
{
    use PlaceHelperPageTrait;
    /**
     * @return list<MoonShineComponent|Field>
     */
    public function fields(): array
    {
        return [
            ID::make()->sortable(),
//            Number::make('Долгота', 'latitude'),
//            Number::make('Широта', 'longitude'),
            Text::make('Адрес', 'address'),
        ];
    }
    protected function itemsComponent(iterable $items, Fields $fields): MoonShineRenderable
    {
        return CardsBuilder::make($items)
            ->fields($fields)
            ->cast($this->getResource()->getModelCast());
//        return Collapse::make('Место провидения')->fields($components)->persist(fn() => false);

//        return parent::itemsComponent($items, $fields); // TODO: Change the autogenerated stub (change table)
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
