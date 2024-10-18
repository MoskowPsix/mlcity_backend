<?php

namespace App\MoonShine\Pages\Event;

use App\Models\Price;
use App\Models\Status;
use App\MoonShine\Resources\HistoryContentResource;
use App\MoonShine\Resources\OrganizationResource;
use App\MoonShine\Resources\PlaceResource;
use App\MoonShine\Resources\PriceResource;
use App\MoonShine\Resources\SightResource;
use App\MoonShine\Resources\StatusResource;
use App\MoonShine\Resources\MoonUserResource;
use MoonShine\ActionButtons\ActionButton;
use MoonShine\Components\Badge;
use MoonShine\Components\Card;
use MoonShine\Components\CardsBuilder;
use MoonShine\Components\FormBuilder;
use MoonShine\Components\Link;
use MoonShine\Decorations\Column;
use MoonShine\Decorations\Fragment;
use MoonShine\Decorations\Grid;
use MoonShine\Fields\ID;
use MoonShine\Fields\Image;
use MoonShine\Fields\Json;
use MoonShine\Fields\Relationships\BelongsTo;
use MoonShine\Fields\Relationships\BelongsToMany;
use MoonShine\Fields\Relationships\HasMany;
use MoonShine\Fields\Relationships\HasOne;
use MoonShine\Fields\Relationships\MorphMany;
use MoonShine\Fields\Select;
use MoonShine\Fields\Text;

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
                    if($status->pivot->last) {
                        $result = $status;
                        break;
                    }
                }
                return Link::make((new StatusResource())->detailPageUrl($result), $result->name);
            });
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
    protected function showCountFavorites(): BelongsToMany
    {
        return BelongsToMany::make('Добавили в избранное', 'favoritesUsers', resource: new MoonUserResource())->onlyLink();
    }
    protected function showPrices():BelongsToMany
    {
        return BelongsToMany::make('Цены', 'prices', resource: new PriceResource());
    }

    protected function showOrganization(): BelongsTo
    {
        return BelongsTo::make('Организация', 'organization', resource: new OrganizationResource())
            ->changePreview(function ($data) {
                return Link::make((new SightResource())->detailPageUrl($data->sight), $data->sight->name);
            });
    }
    protected function showPlaces(): HasMany
    {
        return HasMany::make('Места проведения', 'places', resource: new PlaceResource())
            ->limit(10)
            ->searchable(false);
    }
    protected function showFirsHistoryContent(): MorphMany
    {
        return MorphMany::make('Изменения', 'historyContents', resource: new HistoryContentResource())
            ->searchable(false);
    }

    public function showGridCardPriceUI($prices)
    {
        $cards = [];
        foreach($prices as $price) {
            $cards[] = Column::make([Card::make(
                title: 'Билет',
                values: [
                    'Цена' => $price->cost_rub . ' р.',
                ],
                subtitle: $price->description
            )
            ])->columnSpan(3);
        }
        return Grid::make('Цена',$cards)->customAttributes(['class' => 'mt-8']);
    }
    public function showActionStatusButton()
    {
        return  ActionButton::make(
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
            );
    }
//    public function showCardsForPlaces() {
//        dd($ths->getItems->)
//        return CardsBuilder::make();
//    }
}
