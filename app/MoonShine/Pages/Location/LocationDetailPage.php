<?php

declare(strict_types=1);

namespace App\MoonShine\Pages\Location;

use Illuminate\Database\Eloquent\Model;
use MoonShine\Fields\Checkbox;
use MoonShine\Fields\Code;
use MoonShine\Fields\ID;
use MoonShine\Fields\Json;
use MoonShine\Fields\Markdown;
use MoonShine\Fields\Number;
use MoonShine\Fields\Td;
use MoonShine\Fields\Text;
use MoonShine\Pages\Crud\DetailPage;
use MoonShine\Components\MoonShineComponent;
use MoonShine\Fields\Field;
use MoonShine\Resources\Resource;
use Throwable;

class LocationDetailPage extends DetailPage
{
    /**
     * @return list<MoonShineComponent|Field>
     */
    public function fields(): array
    {
        return [
            ID::make(),
            Text::make('TimeZone', 'time_zone'),
            Text::make('UTC', 'time_zone_utc'),
            Number::make('Latitude', 'latitude'),
            Number::make('Longitude', 'longitude'),
            Checkbox::make('Отображение', 'display'),
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
