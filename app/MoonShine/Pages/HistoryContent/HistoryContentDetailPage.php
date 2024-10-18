<?php

declare(strict_types=1);

namespace App\MoonShine\Pages\HistoryContent;

use App\MoonShine\Resources\HistoryPlaceResource;
use App\MoonShine\Resources\MoonUserResource;
use Illuminate\Database\Eloquent\Relations\HasMany;
use MoonShine\Components\Link;
use MoonShine\Fields\Date;
use MoonShine\Fields\ID;
use MoonShine\Fields\Markdown;
use MoonShine\Fields\Relationships\BelongsTo;
use MoonShine\Fields\Text;
use MoonShine\Pages\Crud\DetailPage;
use MoonShine\Components\MoonShineComponent;
use MoonShine\Fields\Field;
use Throwable;

class HistoryContentDetailPage extends DetailPage
{
    use HistoryContentHelperTrait;
    /**
     * @return list<MoonShineComponent|Field>
     */
    public function fields(): array
    {
        $model_history = $this->getResource()->getItem();
        return [
            ID::make(),
            Text::make('Название', 'name'),
            BelongsTo::make('Автор', 'user', resource: new MoonUserResource())
                ->changePreview(function ($data) {
                    if (empty($data)) {
                        return '';
                    }
                    return Link::make((new MoonUserResource())->detailPageUrl($data), $data->name);
                }),
            Text::make('Организатор', 'sponsor'),
            Date::make('Начало', 'date_start')->format('d.m.Y H:i'),
            Date::make('Конец', 'date_start')->format('d.m.Y H:i'),
            Markdown::make('Описание', 'description'),
            $this->showHistoryPlace(),
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
