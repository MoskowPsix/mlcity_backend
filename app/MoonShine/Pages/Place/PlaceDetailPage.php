<?php

declare(strict_types=1);

namespace App\MoonShine\Pages\Place;

use MoonShine\Decorations\Block;
use MoonShine\Decorations\Fragment;
use MoonShine\Fields\ID;
use MoonShine\Fields\Number;
use MoonShine\Fields\Text;
use MoonShine\Pages\Crud\DetailPage;
use MoonShine\Components\MoonShineComponent;
use MoonShine\Fields\Field;
use Throwable;

class PlaceDetailPage extends DetailPage
{
    use PlaceHelperPageTrait;
    /**
     * @return list<MoonShineComponent|Field>
     */
    public function fields(): array
    {
//        dd($this->getResource()->getItem()->first()->toArray());
        return [
            ID::make()->sortable(),
            Number::make('Долгота', 'latitude'),
            Number::make('Широта', 'longitude'),
            Text::make('Адрес', 'address'),
            $this->showLocation(),
//            $this->showSight(),
            $this->showSeances(),
        ];
    }

    /**
     * @return list<MoonShineComponent>
     * @throws Throwable
     */
    protected function topLayer(): array
    {
        return [
            ...parent::topLayer(),
        ];
    }

    /**
     * @return list<MoonShineComponent>
     * @throws Throwable
     */
    protected function mainLayer(): array
    {
        return [
//            Block::make([
//                $this->showSeances(),
//            ]),
            ...parent::mainLayer(),
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
