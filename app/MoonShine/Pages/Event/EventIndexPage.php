<?php

declare(strict_types=1);

namespace App\MoonShine\Pages\Event;

use App\MoonShine\Resources\LocationResource;
use App\MoonShine\Resources\StatusResource;
use App\MoonShine\Resources\MoonUserResource;
use MoonShine\Components\Link;
use MoonShine\Fields\Date;
use MoonShine\Fields\ID;
use MoonShine\Fields\Relationships\BelongsTo;
use MoonShine\Fields\Relationships\BelongsToMany;
use MoonShine\Fields\Relationships\HasManyThrough;
use MoonShine\Fields\Text;
use MoonShine\Pages\Crud\IndexPage;
use MoonShine\Components\MoonShineComponent;
use MoonShine\Fields\Field;
use Throwable;

class EventIndexPage extends IndexPage
{
    use EventHelperPageTrait;
    /**
     * @return list<MoonShineComponent|Field>
     */
    public function fields(): array
    {
        return [
            ID::make()->sortable(),
            Text::make('Название', 'name')->sortable(),
            BelongsTo::make('Автор', 'author', resource: new MoonUserResource())
                ->changePreview(function ($data) {
                    return Link::make((new MoonUserResource())->detailPageUrl($data), $data->name);
                }),
            Text::make('Организатор', 'name'),
            Date::make('Начало', 'date_start')->format('d.m.Y H:i')->sortable(),
            Date::make('Конец', 'date_start')->format('d.m.Y H:i')->sortable(),
            $this->showLastStatus(),
            Date::make('Создано', 'created_at')->format('d.m.Y H:i')->sortable(),
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
