<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Contracts\Services\EventService\EventService;
use App\Contracts\Services\OrganizationService\OrganizationService;
use App\Contracts\Services\SightService\SightService;
use App\Http\Controllers\Api\StatusController;
use App\Models\Event;
use App\Models\Organization;
use App\Models\Sight;
use Exception;
use Illuminate\Database\Eloquent\Model;
use App\MoonShine\Pages\Event\EventIndexPage;
use App\MoonShine\Pages\Event\EventFormPage;
use App\MoonShine\Pages\Event\EventDetailPage;
use Illuminate\Contracts\Database\Eloquent\Builder;


use Illuminate\Support\Facades\Request;
use MoonShine\Decorations\Block;
use MoonShine\Decorations\Column;
use MoonShine\Decorations\Grid;
use MoonShine\Enums\ClickAction;
use MoonShine\Fields\DateRange;
use MoonShine\Fields\Field;
use MoonShine\Fields\Number;
use MoonShine\Fields\Relationships\BelongsToMany;
use MoonShine\Fields\Text;
use MoonShine\MoonShineRequest;
use MoonShine\MoonShineUI;
use MoonShine\Resources\ModelResource;
use MoonShine\Pages\Page;
use MoonShine\Traits\Resource\ResourceWithParent;

/**
 * @extends ModelResource<Event>
 */
class EventResource extends ModelResource
{
    use ResourceWithParent;

    protected function getParentResourceClassName(): string
    {
        return SightResource::class;
    }

    protected function getParentRelationName(): string
    {
        return 'sight';
    }

    protected string $model = Event::class;

    protected string $title = 'События';

    protected bool $editInModal = true;

    protected bool $saveFilterState = true;

    public static array $activeActions = ['view'];
    protected bool $simplePaginate = true;
    protected bool $usePagination = true;
    protected ?ClickAction $clickAction = ClickAction::DETAIL;
    protected bool $isAsync = true;


    public function search(): array
    {
        return ['id', 'name', 'author.name', 'author.email'];
    }

    public function getActiveActions(): array
    {
        if (auth()->id() === $this->getItem()?->author_id) {
            return static::$activeActions;
        }

        return static::$activeActions;
    }

    public function filters(): array
    {
        return [
            BelongsToMany::make('Города', 'locationsBelongToMany', resource: new LocationResource())
                ->selectMode()
                ->valuesQuery(fn(Builder $query, Field $field) => $query->where('display', '=',true))
            ,
            BelongsToMany::make('Тип', 'types', resource: new EventTypeResource())->selectMode(),
            BelongsToMany::make('статус', 'statuses', resource: new StatusResource())->selectMode()
            ->onApply(function (Builder $query, array $value, Field $field) {
                $query = $query->whereHas('lastStatus', function($q) use($value) {
                    return $q->whereIn('status_id', $value);
                });
                return $query->whereHas('statuses', function($q) {
                    return $q->where('last', true);
                });
            }),
            Number::make('id мероприятия', 'id'),
            Text::make('Имя', 'name'),
            Text::make('Организатор', 'sponsor'),
            Grid::make([
                Column::make([
                    Block::make('Автор', [
                        Number::make('id', 'user_id'),
                        Text::make('Имя', 'author.name'),
                        Text::make('Почта', 'author.email'),
                    ]),
                ])->columnSpan(12),
                Column::make([
                    Block::make('Даты проведения', [
                        DateRange::make('Начало', 'date_start'),
                        DateRange::make('Конец', 'date_end'),
                    ]),
                ])->columnSpan(12),
                Column::make([
                    Block::make('Интеграция', [
                        Text::make('Имя Источника', 'source_name'),
                        Text::make('ID Источника', 'source_id'),
                    ])
                ])->columnSpan(12),
            ]),
        ];
    }

    /**
     * @return list<Page>
     */
    public function pages(): array
    {
        return [
            EventIndexPage::make($this->title()),
            EventFormPage::make(
                $this->getItemID()
                    ? __('moonshine::ui.edit')
                    : __('moonshine::ui.add')
            ),
            EventDetailPage::make(__('moonshine::ui.show')),
        ];
    }

    /**
     * @param Event $item
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
            $eventService->addStatusEvent($request);
            MoonShineUI::toast('Статус изменён!', 'success');
        } catch (Exception $e) {
            MoonShineUI::toast($e->getMessage(), 'error');
        }
    }
    public function transferEvent(MoonShineRequest $request): void
    {
        try {
            $eventService = new EventService();
            $eventService->sightTransferOrganization((int)$request->sight_id, (int)$request->event_id);
            $orgName = Sight::find((int)$request->sight_id);
            if($orgName){
                $org = $orgName->name;
                MoonShineUI::toast("Событие передано новому сообществу с именем: $org, id: $request->sight_id", 'success');
            }

        } catch (Exception $e) {
            MoonShineUI::toast($e->getMessage(), 'error');
        }
    }
}
