<?php

namespace App\MoonShine\Pages\Event;

use App\Models\Price;
use App\Models\Status;
use App\MoonShine\Resources\EventResource;
use App\MoonShine\Resources\HistoryContentResource;
use App\MoonShine\Resources\OrganizationResource;
use App\MoonShine\Resources\PlaceResource;
use App\MoonShine\Resources\PriceResource;
use App\MoonShine\Resources\SightResource;
use App\MoonShine\Resources\StatusResource;
use App\MoonShine\Resources\MoonUserResource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use MoonShine\ActionButtons\ActionButton;
use MoonShine\Components\Badge;
use MoonShine\Components\Card;
use MoonShine\Components\CardsBuilder;
use MoonShine\Components\FormBuilder;
use MoonShine\Components\Layout\Search;
use MoonShine\Components\Link;
use MoonShine\Decorations\Column;
use MoonShine\Decorations\Fragment;
use MoonShine\Decorations\Grid;
use MoonShine\Fields\ID;
use MoonShine\Fields\Image;
use MoonShine\Fields\Json;
use MoonShine\Fields\Number;
use MoonShine\Fields\Relationships\BelongsTo;
use MoonShine\Fields\Relationships\BelongsToMany;
use MoonShine\Fields\Relationships\HasMany;
use MoonShine\Fields\Relationships\HasOne;
use MoonShine\Fields\Relationships\MorphMany;
use MoonShine\Fields\Select;
use MoonShine\Fields\Text;
use stdClass;

trait EventHelperPageTrait
{
    protected function showLastStatus(): BelongsToMany
    {
        return BelongsToMany::make('Cтатус', 'statuses', resource: new StatusResource())
            ->changePreview(function ($data) {
                $result = [];
                if (empty($data->toArray())) {
                    return '<p style="color: red">Нет статуса<p>';
                }
                foreach ($data as $status) {
                    if ($status->pivot->last) {
                        $result = $status;
                        break;
                    }
                }
                return Link::make((new StatusResource())->detailPageUrl($result), $result->name);
            })->sortable(function (Builder $query, string $column, string $direction) {
//                dd($this->getResource()->getModelCast());
                if ($this->getResource()->getModelCast() == 'App\Models\Event') {
                    return $query
                        ->join('event_status', 'event_status.event_id', '=', 'events.id')
                        ->join('statuses', 'event_status.status_id', '=', 'statuses.id')
                        ->orderBy($column, $direction);
                } else if ($this->getResource()->getModelCast() == 'App\Models\Sight') {
                    return $query
                        ->join('sight_status', 'sight_status.sight_id', '=', 'sights.id')
                        ->join('statuses', 'sight_status.status_id', '=', 'statuses.id')
                        ->orderBy($column, $direction);
                }
            });
    }

    protected function getCurrentStatus(): Model|stdClass
    {
        $statuses = $this->getResource()->getItem()->statuses;
        $result = new stdClass();
        $result->name = 'Нет статуса';
        foreach ($statuses as $status) {
            if ($status->pivot->last) {
                $result = $status;
                break;
            }
        }
        return $result;
    }

    protected function showGallery()
    {
        return Image::make('Картинки', 'files')
            ->changePreview(function ($data) {
                if (empty($data)) {
                    return '';
                }
                $res = [];
                foreach ($data as $file) {
                    $res[] = view('moonshine::ui.image', ['value' => $file->link]);
                }
                return view('moonshine::ui.image', ['value' => $data]);
            });
    }

    protected function showCountLikes(): BelongsToMany
    {
        return BelongsToMany::make('Понравилось', 'likedUsers', resource: new MoonUserResource())->onlyLink();
    }
    protected function showCountEvents(): HasMany
    {
        return HasMany::make('События', 'organizationEvents', resource: new EventResource())->searchable(false);
    }

    protected function showCountFavorites(): BelongsToMany
    {
        return BelongsToMany::make('Добавили в избранное', 'favoritesUsers', resource: new MoonUserResource())->onlyLink();
    }

    protected function showPrices(): BelongsToMany
    {
        return BelongsToMany::make('Цены', 'prices', resource: new PriceResource());
    }

    protected function showOrganization(): BelongsTo
    {
        return BelongsTo::make('Организация', 'organization', resource: new OrganizationResource())
            ->changePreview(function ($data) {
                return isset($data->sight) ? Link::make((new SightResource())->detailPageUrl($data->sight), $data->sight->name) : 'Отсутствует';
            });
    }

    protected function showPlaces(): HasMany
    {
        return HasMany::make('Места проведения', 'places', resource: new PlaceResource())
            ->searchable(false);
    }

    protected function showFirsHistoryContent(): MorphMany
    {
        return MorphMany::make('Изменения', 'historyContents', resource: new HistoryContentResource())
            ->searchable(false);
    }

    public function showActionStatusButton()
    {
        return ActionButton::make(
            label: 'Сменить статус',
        )
            ->customAttributes(['class' => 'mt-8'])
            ->icon('heroicons.sparkles')
            ->secondary()
            ->inModal(
                title: fn() => 'Сменить статус',
                content: function () {
                    $user = auth('moonshine')->user();
                    return (string)FormBuilder::make()
                        ->async(asyncEvents: ['testMethod'])
                        ->fields([
                            ID::make('ID', 'event_id')->hideOnAll()->setValue($this->getResource()->getItem()->id),
                            Select::make('Статус', 'status')
                                ->options(collect(Status::all())->pluck('name', 'name')->all())
                        ])->submit('Сменить')
                        ->asyncMethod('changeStatus');
                },
            );
    }

    public function showActionStatusButtonForSight()
    {
        return ActionButton::make(
            label: 'Сменить статус',
        )
            ->customAttributes(['class' => 'mt-8'])
            ->icon('heroicons.sparkles')
            ->secondary()
            ->inModal(
                title: fn() => 'Сменить статус',
                content: function () {
                    $user = auth('moonshine')->user();
                    return (string)FormBuilder::make()
                        ->async(asyncEvents: ['testMethod'])
                        ->fields([
                            ID::make('ID', 'sight_id')->hideOnAll()->setValue($this->getResource()->getItem()->id),
                            Select::make('Статус', 'status')
                                ->options(collect(Status::all())->pluck('name', 'name')->all())
                        ])->submit('Сменить')
                        ->asyncMethod('changeStatus');
                },
            );
    }

    public function transferSight()
    {
            return ActionButton::make(
                label: 'Передать другому пользователю',
            )
                ->customAttributes(['class' => 'mt-8'])
                ->icon('heroicons.sparkles')
                ->primary()
                ->canSee(function() {
                    return auth('moonshine')->user()->hasRole('Admin') || auth('moonshine')->user()->hasRole('root');
                })
                ->inModal(
                    title: fn() => 'Передать сообщество',
                    content: function () {
                        return (string)FormBuilder::make()
                            ->async(asyncEvents: ['transfer'])
                            ->fields([
                                Number::make('ID пользователя, которому передаётся сообщество и его события', 'user_id')->setValue($this->getResource()->getItem()->user_id), // Более правильное название поля
                                ID::make('ID', 'organization_id')->hideOnAll()->setValue($this->getResource()->getItem()->organization->id), // Более правильное название поля
                            ])->submit('Сменить')
                            ->asyncMethod('transferSight');
                    },
                );
    }
}
