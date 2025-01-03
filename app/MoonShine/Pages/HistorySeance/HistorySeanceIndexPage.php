<?php

declare(strict_types=1);

namespace App\MoonShine\Pages\HistorySeance;

use MoonShine\Fields\Date;
use MoonShine\Fields\ID;
use MoonShine\Pages\Crud\IndexPage;
use MoonShine\Components\MoonShineComponent;
use MoonShine\Fields\Field;
use Throwable;

class HistorySeanceIndexPage extends IndexPage
{
    use HistorySeanceHelperTrait;
    /**
     * @return list<MoonShineComponent|Field>
     */
    public function fields(): array
    {
        return [
            ID::make(),
            $this->showOriginId(),
            Date::make('Начало', 'date_start'),
            Date::make('Конец', 'date_end'),

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
