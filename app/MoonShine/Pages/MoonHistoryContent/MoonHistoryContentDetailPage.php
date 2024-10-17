<?php

declare(strict_types=1);

namespace App\MoonShine\Pages\MoonHistoryContent;

use App\MoonShine\Resources\MoonHistoryPlaceResource;
use Illuminate\Database\Eloquent\Relations\HasMany;
use MoonShine\Fields\Date;
use MoonShine\Fields\ID;
use MoonShine\Fields\Markdown;
use MoonShine\Fields\Text;
use MoonShine\Pages\Crud\DetailPage;
use MoonShine\Components\MoonShineComponent;
use MoonShine\Fields\Field;
use Throwable;

class MoonHistoryContentDetailPage extends DetailPage
{
    use MoonHistoryContentHelperTrait;
    /**
     * @return list<MoonShineComponent|Field>
     */
    public function fields(): array
    {
        $model_history = $this->getResource()->getItem();
        return [
            ID::make(),
            Text::make('Название', 'name')->hideOnDetail(isset($model_history->name)),
            Text::make('Организатор', 'sponsor')->hideOnDetail(isset($model_history->sponsor)),
            Date::make('Начало', 'date_start')->hideOnDetail(isset($model_history->date_start))->format('d.m.Y H:i'),
            Date::make('Конец', 'date_start')->hideOnDetail(isset($model_history->date_start))->format('d.m.Y H:i'),
            Markdown::make('Описание', 'description')->hideOnDetail(isset($model_history->description)),
            Text::make('Организатор', 'sponsor'),
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
