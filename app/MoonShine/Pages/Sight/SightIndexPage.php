<?php

declare(strict_types=1);

namespace App\MoonShine\Pages\Sight;

use App\Models\HistoryContent;
use App\Models\Sight;
use App\MoonShine\Pages\Event\EventHelperPageTrait;
use App\MoonShine\Pages\Organization\OrganizationHelperPageTrait;
use App\MoonShine\Resources\EventTypeResource;
use App\MoonShine\Resources\HistoryContentResource;
use App\MoonShine\Resources\LocationResource;
use App\MoonShine\Resources\MoonUserResource;
use App\MoonShine\Resources\SightTypeResource;
use MoonShine\Components\Link;
use MoonShine\Fields\Date;
use MoonShine\Fields\ID;
use MoonShine\Fields\Relationships\BelongsTo;
use MoonShine\Fields\Relationships\BelongsToMany;
use MoonShine\Fields\Relationships\MorphMany;
use MoonShine\Fields\Text;
use MoonShine\Pages\Crud\IndexPage;
use MoonShine\Components\MoonShineComponent;
use MoonShine\Fields\Field;
use Throwable;

class SightIndexPage extends IndexPage
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
            BelongsTo::make('Город', 'locations', resource: new LocationResource())->sortable(),
            BelongsToMany::make('Тип', 'types', resource: new SightTypeResource())
                ->inLine(
                    separator: ', ',
                )
            ,
            $this->showLastStatus(),
            Date::make('Создано', 'created_at')->format('d.m.Y H:i')->sortable(),

//            Date::make('Изменено', 'historyContents')->setValue($this->getResource()->getItem()->historyContents()->first()->created_at),
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
