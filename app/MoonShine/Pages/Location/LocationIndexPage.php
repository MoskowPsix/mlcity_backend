<?php

declare(strict_types=1);

namespace App\MoonShine\Pages\Location;

use App\MoonShine\Resources\EventResource;
use App\MoonShine\Resources\LocationResource;
use MoonShine\Fields\Checkbox;
use MoonShine\Fields\ID;
use MoonShine\Fields\Json;
use MoonShine\Fields\Number;
use MoonShine\Fields\Relationships\HasManyThrough;
use MoonShine\Fields\Text;
use MoonShine\Pages\Crud\IndexPage;
use MoonShine\Components\MoonShineComponent;
use MoonShine\Fields\Field;
use Throwable;

class LocationIndexPage extends IndexPage
{
    /**
     * @return list<MoonShineComponent|Field>
     */
    public function fields(): array
    {
        return [
            ID::make()->sortable(),
            Text::make('Назание', 'name')->sortable(),
//            HasManyThrough::make('Количество Событий', 'events', resource: new EventResource())->onlyLink(),
            Text::make('TimeZone', 'time_zone'),
            Text::make('UTC', 'time_zone_utc'),
            Number::make('Latitude', 'latitude'),
            Number::make('Longitude', 'longitude'),
            Checkbox::make('Отображение', 'display')
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
