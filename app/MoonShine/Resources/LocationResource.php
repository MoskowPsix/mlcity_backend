<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\Location;
use Illuminate\Database\Eloquent\Model;
use App\MoonShine\Pages\Location\LocationIndexPage;
use App\MoonShine\Pages\Location\LocationFormPage;
use App\MoonShine\Pages\Location\LocationDetailPage;

use MoonShine\Resources\ModelResource;
use MoonShine\Pages\Page;

/**
 * @extends ModelResource<Location>
 */
class LocationResource extends ModelResource
{
    protected string $model = Location::class;

    protected string $column = 'name';

    protected string $title = 'Города';
    protected string $sortColumn = 'locations.id';

    protected array $with = ['events'];
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
     * @return list<Page>
     */
    public function pages(): array
    {
        return [
            LocationIndexPage::make($this->title()),
            LocationFormPage::make(
                $this->getItemID()
                    ? __('moonshine::ui.edit')
                    : __('moonshine::ui.add')
            ),
            LocationDetailPage::make(__('moonshine::ui.show')),
        ];
    }

    /**
     * @param Location $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    public function rules(Model $item): array
    {
        return [];
    }
}
