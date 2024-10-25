<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Http\Controllers\Api\StatusController;
use App\Models\Sight;
use Exception;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\MoonShine\Pages\Sight\SightIndexPage;
use App\MoonShine\Pages\Sight\SightFormPage;
use App\MoonShine\Pages\Sight\SightDetailPage;

use MoonShine\Fields\Relationships\BelongsTo;
use MoonShine\MoonShineRequest;
use MoonShine\MoonShineUI;
use MoonShine\Decorations\Block;
use MoonShine\Decorations\Column;
use MoonShine\Decorations\Grid;
use MoonShine\Fields\DateRange;
use MoonShine\Fields\Field;
use MoonShine\Fields\Number;
use MoonShine\Fields\Relationships\BelongsToMany;
use MoonShine\Fields\Text;
use MoonShine\Resources\ModelResource;
use MoonShine\Pages\Page;

/**
 * @extends ModelResource<Sight>
 */
class SightResource extends ModelResource
{
    protected string $model = Sight::class;

    protected string $title = 'Места';
    protected bool $saveFilterState = true;

    protected string $column = 'name';
    public static array $activeActions = ['view'];
    protected bool $simplePaginate = true;
    protected bool $isAsync = true;

    public function search(): array
    {
        return ['id', 'name'];
    }

    public function getActiveActions(): array
    {
        if (auth()->id() === $this->getItem()?->author_id) {
            return static::$activeActions;
        }

        return static::$activeActions;
    }

    /**
     * @return list<Page>
     */
    public function pages(): array
    {
        return [
            SightIndexPage::make($this->title()),
            SightFormPage::make(
                $this->getItemID()
                    ? __('moonshine::ui.edit')
                    : __('moonshine::ui.add')
            ),
            SightDetailPage::make(__('moonshine::ui.show')),
        ];
    }

    public function filters(): array
    {
        return [
            BelongsTo::make('Города', 'locations', resource: new LocationResource())->searchable()
                ->valuesQuery(fn(Builder $query, Field $field) => $query->where('display', '=',true))
            ,
            BelongsToMany::make('Тип', 'types', resource: new EventTypeResource())->selectMode(),
            BelongsToMany::make('Статус', 'statuses', resource: new StatusResource())->selectMode()
                ->onApply(function (Builder $query, array $value, Field $field) {
                    $query = $query->whereHas('statuses', function($q) use($value) {
                        return $q->whereIn('status_id', $value);
                    });
                    return $query->whereHas('statuses', function($q) {
                        return $q->where('last', true);
                    });
                }),
            Number::make('Id события', 'id'),
            Text::make('Название', 'name'),
            Grid::make([
                Column::make([
                    Block::make('Автор', [
                        Number::make('id', 'user_id'),
                        Text::make('Имя', 'author.name'),
                        Text::make('Почта', 'author.email'),
                    ]),
                ])
            ])
        ];
    }

    /**
     * @param Sight $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    public function rules(Model $item): array
    {
        return [];
    }
    public function changeStatus(MoonShineRequest $request): void
    {
        try {
            $eventService = new StatusController();
            $eventService->addStatusSight($request);
            MoonShineUI::toast('Статус изменён!', 'success');
        } catch (Exception $e) {
            MoonShineUI::toast($e->getMessage(), 'error');
        }
    }
}
