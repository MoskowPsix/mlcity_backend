<?php

declare(strict_types=1);

namespace App\MoonShine\Pages\MoonEvent;

use App\Models\Status;
use App\Models\User;
use App\MoonShine\Resources\MoonHistoryPlaceResource;
use App\MoonShine\Resources\MoonPlaceResource;
use App\MoonShine\Resources\MoonStatusResource;
use App\MoonShine\Resources\MoonUserResource;
use Google\Service\Transcoder\TextInput;
use Illuminate\Database\Eloquent\Model;
use MoonShine\ActionButtons\ActionButton;
use MoonShine\Components\Card;
use MoonShine\Components\Carousel;
use MoonShine\Components\FormBuilder;
use MoonShine\Components\Layout\Div;
use MoonShine\Components\Link;
use MoonShine\Decorations\Block;
use MoonShine\Decorations\Column;
use MoonShine\Decorations\Grid;
use MoonShine\Decorations\TextBlock;
use MoonShine\Fields\Date;
use MoonShine\Fields\ID;
use MoonShine\Fields\Image;
use MoonShine\Fields\Markdown;
use MoonShine\Fields\Relationships\BelongsTo;
use MoonShine\Fields\Relationships\BelongsToMany;
use MoonShine\Fields\Relationships\HasMany;
use MoonShine\Fields\Select;
use MoonShine\Fields\Text;
use MoonShine\MoonShineUI;
use MoonShine\Pages\Crud\DetailPage;
use MoonShine\Components\MoonShineComponent;
use MoonShine\Fields\Field;
use Throwable;

class MoonEventDetailPage extends DetailPage
{
    use MoonEventHelperPageTrait;
    /**
     * @return list<MoonShineComponent|Field>
     */
    public function fields(): array
    {
        if ($this->getCurrentStatus()->name == 'Изменено') {
            return $this->showForDetailChange();
        } else {
            return $this->showForDetail();
        }
    }

    /**
     * @return list<MoonShineComponent>
     * @throws Throwable
     */
    protected function topLayer(): array
    {
        if ($this->getCurrentStatus()->name == 'Изменено'){
            return [
                ...parent::topLayer()
            ];
        } else {
            return [
                Div::make('Билеты', [
                    Carousel::make(
                        items: collect($this->getResource()->getItem()->files)->pluck('link')->all(),
                        portrait: false,
                    )
                ]),
                ...parent::topLayer()
            ];
        }
    }
    private function getCurrentStatus(): Model
    {
        $statuses = $this->getResource()->getItem()->statuses;
        $result = '';
        foreach ($statuses as $status) {
            if($status->pivot->last) {
                $result = $status;
                break;
            }
        }
        return $result;
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
        $cards = [];
        foreach($this->getResource()->getItem()->prices as $price) {
            $cards[] = Column::make([Card::make(
                    title: 'Билет',
                    values: [
                        'Цена' => $price->cost_rub . ' р.',
                    ],
                    subtitle: $price->description
                )
            ])->columnSpan(3);
        }
        return [
            Grid::make('Цена',$cards)->customAttributes(['class' => 'mt-8']),
            ...parent::bottomLayer(),
            ActionButton::make(
                label: 'Сменить статус',
            )
                ->customAttributes(['class' => 'mt-8'])
                ->icon('heroicons.sparkles')
                ->secondary()
                ->inModal(
                    title: fn() => 'Modal title',
                    content: function() {
                        $user = auth('moonshine')->user();
                        return (string)FormBuilder::make()
                            ->async(asyncEvents: ['testMethod'])
                            ->fields([
                                Select::make('Статус', 'status_id')
                                    ->options(collect(Status::all())->pluck('name', 'id')->all())
                            ])->submit('Сменить')
                            ->asyncMethod('changeStatus');
                    },
                )
        ];
    }
    private function showForDetailChange(): array
    {
        $model_history = $this->getResource()->getItem()->historyContents()->first();
        return [
//            $this->showLastStatus(),
//            ID::make('ID Изменения')->setValue($model_history->id),
//            Text::make('Название')->hideOnDetail(!isset($model_history->name))->setValue($model_history->name),
//            Text::make('Организатор')->hideOnDetail(!isset($model_history->sponsor))->setValue($model_history->sponsor),
//            Date::make('Начало')->hideOnDetail(!isset($model_history->date_start))->setValue($model_history->date_start)->format('d.m.Y H:i'),
//            Date::make('Конец')->hideOnDetail(!isset($model_history->date_start))->setValue($model_history->date_start)->format('d.m.Y H:i'),
//            Markdown::make('Описание')->hideOnDetail(!isset($model_history->description))->setValue($model_history->description),
            $this->showFirsHistoryContent(),
//            HasMany::make('Места проведения', 'history_places', resource: new MoonHistoryPlaceResource())->setResource($model_history),
//            (new MoonHistoryPlaceResource())->detailPageUrl($model_history->history_places),
        ];
    }
    private function showForDetail(): array
    {
        return [
            $this->showLastStatus(),
            $this->showCountLikes(),
            $this->showCountFavorites(),
            ID::make(),
            Text::make('Название', 'name'),
            BelongsTo::make('Автор', 'author', resource: new MoonUserResource())
                ->changePreview(function ($data) {
                    return Link::make((new MoonUserResource())->detailPageUrl($data), $data->name);
                }),
            Text::make('Организатор', 'sponsor'),
            Date::make('Начало', 'date_start')->format('d.m.Y H:i'),
            Date::make('Конец', 'date_start')->format('d.m.Y H:i'),
            Markdown::make('Описание', 'description'),
            $this->showPlaces(),

            $this->showOrganization(),
        ];
    }
}
