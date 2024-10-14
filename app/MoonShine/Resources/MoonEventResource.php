<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\Event;
use Illuminate\Database\Eloquent\Model;
use App\MoonShine\Pages\MoonEvent\MoonEventIndexPage;
use App\MoonShine\Pages\MoonEvent\MoonEventFormPage;
use App\MoonShine\Pages\MoonEvent\MoonEventDetailPage;

use MoonShine\Components\Link;
use MoonShine\Fields\Date;
use MoonShine\Fields\ID;
use MoonShine\Fields\Relationships\BelongsTo;
use MoonShine\Fields\Text;
use MoonShine\Resources\ModelResource;
use MoonShine\Pages\Page;

/**
 * @extends ModelResource<Event>
 */
class MoonEventResource extends ModelResource
{
    protected string $model = Event::class;

    protected string $title = 'События';

    public function fields(): array
    {
        return [
            ID::make(),
            Text::make('Название', 'name'),
            BelongsTo::make('Автор', 'author', resource: new MoonUserResource())
                ->changePreview(function ($data) {
                    return Link::make((new MoonUserResource())->detailPageUrl($data), $data->name);
                }),
            Text::make('Организатор', 'name'),
            Date::make('Начало', 'date_start'),
            Date::make('Конец', 'date_start'),
        ];
    }

    public function indexFields(): array
    {
        return[
            ID::make(),
            Text::make('Название', 'name'),
            BelongsTo::make('Автор', 'author', resource: new MoonUserResource())
                ->changePreview(function ($data) {
                    return Link::make((new MoonUserResource())->detailPageUrl($data), $data->name);
                }),
            Text::make('Организатор', 'name'),
            Date::make('Начало', 'date_start')->format('d.m.Y H:i'),
            Date::make('Конец', 'date_start')->format('d.m.Y H:i'),
        ];
    }

    /**
     * @return list<Page>
     */
    public function pages(): array
    {
        return [
            MoonEventIndexPage::make($this->title()),
            MoonEventFormPage::make(
                $this->getItemID()
                    ? __('moonshine::ui.edit')
                    : __('moonshine::ui.add')
            ),
            MoonEventDetailPage::make(__('moonshine::ui.show')),
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
}
