<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\Event;
use App\Models\Status;
use App\MoonShine\Fields\Files;
use GianTiaga\MoonshineCoordinates\Fields\Coordinates;
use http\Client\Request;
use Illuminate\Database\Eloquent\Model;
use App\MoonShine\Pages\MoonEvent\MoonEventIndexPage;
use App\MoonShine\Pages\MoonEvent\MoonEventFormPage;
use App\MoonShine\Pages\MoonEvent\MoonEventDetailPage;
use Illuminate\Contracts\Database\Eloquent\Builder;


use MoonShine\Components\Carousel;
use MoonShine\Components\Link;
use MoonShine\Decorations\Block;
use MoonShine\Decorations\Column;
use MoonShine\Decorations\Grid;
use MoonShine\Fields\Checkbox;
use MoonShine\Fields\Date;
use MoonShine\Fields\DateRange;
use MoonShine\Fields\Field;
use MoonShine\Fields\File;
use MoonShine\Fields\ID;
use MoonShine\Fields\Image;
use MoonShine\Fields\Number;
use MoonShine\Fields\Relationships\BelongsTo;
use MoonShine\Fields\Relationships\BelongsToMany;
use MoonShine\Fields\Relationships\HasMany;
use MoonShine\Fields\Text;
use MoonShine\Metrics\ValueMetric;
use MoonShine\Resources\ModelResource;
use MoonShine\Pages\Page;
use MoonShine\Resources\Resource;
use Webmatherfacker\MoonshineCarousel\Components\Slider;

/**
 * @extends ModelResource<Event>
 */
class MoonEventResource extends ModelResource
{
    protected string $model = Event::class;

    protected string $title = 'События';

    protected bool $saveFilterState = true;

    public function search(): array
    {
        return ['id', 'name', 'author.name', 'author.email'];
    }

    public function filters(): array
    {
        return [
            BelongsToMany::make('статус', 'statuses', resource: new MoonStatusResource())->selectMode()
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
                        DateRange::make('Начало', 'date_start')->fromTo('date_from', 'date_to'),
                        DateRange::make('Конец', 'date_end')->fromTo('date_from', 'date_to'),
                    ]),
                ])->columnSpan(12),
            ])
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
