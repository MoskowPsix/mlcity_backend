<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\MoonType;
use App\Models\EventType;
use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Fields\ID;
use MoonShine\Fields\Image;
use MoonShine\Fields\Text;
use MoonShine\Fields\Number;
use MoonShine\Fields\Field;
use Illuminate\Support\Facades\Storage;
use MoonShine\Components\MoonShineComponent;

/**
 * @extends ModelResource<MoonType>
 */
class EventTypeResource extends ModelResource
{
    protected string $model = EventType::class;

    protected string $title = 'Типы событий';
    protected bool $simplePaginate = true;
    protected bool $isAsync = true;
    public static array $activeActions = ['view'];

    public function getActiveActions(): array
    {
        if (auth()->id() === $this->getItem()?->author_id) {
            return static::$activeActions;
        }

        return static::$activeActions;
    }

    public function search(): array
    {
        return ['id', 'name'];
    }

    /**
     * @return list<MoonShineComponent|Field>
     */

    public function indexFields(): array
    {
        $url = env('APP_URL');
        return [
            ID::make()->sortable(),
            Text::make('Название', 'name')->sortable(),
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
        return [
            Text::make('Название', 'name'),
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
            Text::make('Название', 'name')->required()->placeholder('Название события'),
            Image::make('Иконка', 'ico')
                ->onApply(function (Model $item, $value, Field $field) {
                    $storage = 'public';
                    $puth = "type-event/$item->id";
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
}
