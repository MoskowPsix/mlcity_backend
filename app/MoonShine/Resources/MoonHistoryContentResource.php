<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\HistoryContent;
use Illuminate\Database\Eloquent\Model;
use App\MoonShine\Pages\MoonHistoryContent\MoonHistoryContentIndexPage;
use App\MoonShine\Pages\MoonHistoryContent\MoonHistoryContentFormPage;
use App\MoonShine\Pages\MoonHistoryContent\MoonHistoryContentDetailPage;

use MoonShine\Resources\ModelResource;
use MoonShine\Pages\Page;

/**
 * @extends ModelResource<HistoryContent>
 */
class MoonHistoryContentResource extends ModelResource
{
    protected string $model = HistoryContent::class;

    protected string $title = 'MoonHistoryContents';

    /**
     * @return list<Page>
     */
    public function pages(): array
    {
        return [
            MoonHistoryContentIndexPage::make($this->title()),
            MoonHistoryContentFormPage::make(
                $this->getItemID()
                    ? __('moonshine::ui.edit')
                    : __('moonshine::ui.add')
            ),
            MoonHistoryContentDetailPage::make(__('moonshine::ui.show')),
        ];
    }

    /**
     * @param HistoryContent $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    public function rules(Model $item): array
    {
        return [];
    }
}
