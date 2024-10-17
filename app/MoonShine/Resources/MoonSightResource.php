<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\Sight;
use Illuminate\Database\Eloquent\Model;
use App\Models\MoonSight;
use App\MoonShine\Pages\MoonSight\MoonSightIndexPage;
use App\MoonShine\Pages\MoonSight\MoonSightFormPage;
use App\MoonShine\Pages\MoonSight\MoonSightDetailPage;

use MoonShine\Resources\ModelResource;
use MoonShine\Pages\Page;

/**
 * @extends ModelResource<Sight>
 */
class MoonSightResource extends ModelResource
{
    protected string $model = Sight::class;

    protected string $title = 'Места';

    /**
     * @return list<Page>
     */
    public function pages(): array
    {
        return [
            MoonSightIndexPage::make($this->title()),
            MoonSightFormPage::make(
                $this->getItemID()
                    ? __('moonshine::ui.edit')
                    : __('moonshine::ui.add')
            ),
            MoonSightDetailPage::make(__('moonshine::ui.show')),
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
