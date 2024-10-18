<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\Sight;
use Illuminate\Database\Eloquent\Model;
use App\Models\MoonSight;
use App\MoonShine\Pages\Sight\SightIndexPage;
use App\MoonShine\Pages\Sight\SightFormPage;
use App\MoonShine\Pages\Sight\SightDetailPage;

use MoonShine\Resources\ModelResource;
use MoonShine\Pages\Page;

/**
 * @extends ModelResource<Sight>
 */
class SightResource extends ModelResource
{
    protected string $model = Sight::class;

    protected string $title = 'Места';

    /**
     * @return list<Page>
     */
    public function pages(): array
    {
        return [
            SightIndexPage::make($this->title()),
            SightFormPage::make(
                $this->getItemID()
                    ? __('moonshine::ui.edit')
                    : __('moonshine::ui.add')
            ),
            SightDetailPage::make(__('moonshine::ui.show')),
        ];
    }

    /**
     * @param Sight $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    public function rules(Model $item): array
    {
        return [];
    }
}
