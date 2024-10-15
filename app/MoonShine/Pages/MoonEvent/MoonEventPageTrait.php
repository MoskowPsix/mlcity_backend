<?php

namespace App\MoonShine\Pages\MoonEvent;

use App\Models\Price;
use App\MoonShine\Resources\MoonPriceResource;
use App\MoonShine\Resources\MoonStatusResource;
use App\MoonShine\Resources\MoonUserResource;
use MoonShine\Components\Badge;
use MoonShine\Components\Link;
use MoonShine\Fields\ID;
use MoonShine\Fields\Image;
use MoonShine\Fields\Relationships\BelongsTo;
use MoonShine\Fields\Relationships\BelongsToMany;
use MoonShine\Fields\Relationships\HasMany;
use MoonShine\Fields\Relationships\HasOne;
use MoonShine\Fields\Text;

trait MoonEventPageTrait
{
    protected function showLastStatus(): BelongsToMany
    {
        return BelongsToMany::make('статус', 'statuses', resource: new MoonStatusResource())
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
                return Link::make((new MoonStatusResource())->detailPageUrl($result), $result->name);
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
        return BelongsToMany::make('Цены', 'prices', resource: new MoonPriceResource());
    }

//    protected function showOrganization(): BelongsTo
//    {
////        return BelongsTo::make('Организация', 'organization', resource: new MoonUserResource());
//    }
}
