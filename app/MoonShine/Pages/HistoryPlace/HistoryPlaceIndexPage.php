<?php

declare(strict_types=1);

namespace App\MoonShine\Pages\HistoryPlace;

use Illuminate\Database\Eloquent\Model;
use MoonShine\Fields\ID;
use MoonShine\Fields\Number;
use MoonShine\Pages\Crud\IndexPage;
use MoonShine\Components\MoonShineComponent;
use MoonShine\Fields\Field;
use Throwable;

class HistoryPlaceIndexPage extends IndexPage
{
    use HistoryPlaceHelperTrait;
    /**
     * @return list<MoonShineComponent|Field>
     */
    public function fields(): array
    {
        return [
            ID::make(),
            $this->showOriginId(),
//            Number::make('Долгота', 'latitude'),
//            Number::make('Широта', 'longitude'),
            Number::make('Адрес', 'address'),
//            $this->showSeances(),
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
