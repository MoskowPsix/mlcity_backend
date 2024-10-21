<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\MoonType;
use App\Models\SightType;
use MoonShine\ActionButtons\ActionButton;
use MoonShine\Enums\JsEvent;
use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Fields\ID;
use MoonShine\Fields\Image;
use MoonShine\Fields\Text;
use MoonShine\Fields\Number;
use MoonShine\Fields\Field;
use Illuminate\Support\Facades\Storage;
use MoonShine\Components\MoonShineComponent;
use MoonShine\Support\AlpineJs;

/**
 * @extends ModelResource<MoonType>
 */
class SightTypeResource extends ModelResource
{
    protected string $model = SightType::class;

    protected string $title = 'Типы мест';

    /**
     * @return list<MoonShineComponent|Field>
     */
    // public function fields(): array
    // {
    //     $url = env('APP_URL');
    //     return [
    //         Block::make([
    //             ID::make()->sortable(),
    //             Text::make()->sortable(),
    //             Image::make('Иконка', 'image')
    //                 ->changePreview(function ($data) use ($url) {
    //                     if (empty($data)) {
    //                         return '';
    //                     }

    //                     if (substr($data, 0, 4) == 'http') {
    //                         return view('moonshine::ui.image', ['value' => $data]);
    //                     } else if (substr($data, 0, 4) == '/sto') {
    //                         return view('moonshine::ui.image', ['value' => $url . $data]);
    //                     } else {
    //                         return $data;
    //                     }
    //                 }),

    //         ]),
    //     ];
    // }

    public function indexFields(): array
    {
        $url = env('APP_URL');
        return [
            ID::make()->sortable(),
            Text::make('Названия', 'name')->sortable(),
            Image::make('Иконка', 'ico')
                ->changePreview(function ($data) use ($url) {
                    if (empty($data)) {
                        return '';
                    }

                    if (substr($data, 0, 4) == 'http') {
                        return view('moonshine::ui.image', ['value' => $data]);
                    } else if (substr($data, 0, 4) == '/sto') {
                        return view('moonshine::ui.image', ['value' => $url . $data]);
                    } else {
                        return $data;
                    }
                }),
            Number::make('Порядок', 'order')->sortable()
        ];
    }

    public function detailFields(): array
    {
        $url = env('APP_URL');
        return  [
            Text::make('Названиe', 'name'),
            Image::make('Иконка', 'ico')
                ->changePreview(function ($data) use ($url) {
                    if (empty($data)) {
                        return '';
                    }

                    if (substr($data, 0, 4) == 'http') {
                        return view('moonshine::ui.image', ['value' => $data]);
                    } else if (substr($data, 0, 4) == '/sto') {
                        return view('moonshine::ui.image', ['value' => $url . $data]);
                    } else {
                        return $data;
                    }
                }),
            Number::make('Порядок', 'order')->sortable()
        ];
    }

    public function formFields(): array
    {
        $url = env('APP_URL');
        return [
            Text::make('Названиe', 'name')->required()->placeholder('Название места'),
            Image::make('Иконка', 'ico')
                ->onApply(function (Model $item, $value, Field $field) {
                    $storage = 'public';
                    $puth = "type-sight/$item->id";
                    if ($value) {
                        $save = Storage::disk($storage)->put($puth, $value);
                        $item->ico = "/storage/$save";
                    }
                    return $item;
                })
                ->changeFill(function () use ($url) {
                    return '';
                }),
            Number::make('Порядок', 'order')->sortable()
        ];
    }

    /**
     * @param SightType $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    public function rules(Model $item): array
    {
        return [];
    }
    private function showImage(): Image
    {
        return Image::make('Иконка', 'image');
    }
//    public function actions(): array
//    {
//        return [
//            ActionButton::make('Refresh')
//                ->dispatchEvent(AlpineJs::event(JsEvent::TABLE_UPDATED, 'index-table'))
//        ];
//    }
}
