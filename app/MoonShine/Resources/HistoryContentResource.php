<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\HistoryContent;
use Illuminate\Database\Eloquent\Model;
use App\MoonShine\Pages\HistoryContent\HistoryContentIndexPage;
use App\MoonShine\Pages\HistoryContent\HistoryContentFormPage;
use App\MoonShine\Pages\HistoryContent\HistoryContentDetailPage;

use MoonShine\Resources\ModelResource;
use MoonShine\Pages\Page;

/**
 * @extends ModelResource<HistoryContent>
 */
class HistoryContentResource extends ModelResource
{
    protected string $model = HistoryContent::class;

    protected string $title = 'MoonHistoryContents';

    /**
     * @return list<Page>
     */
    public function pages(): array
    {
        return [
            HistoryContentIndexPage::make($this->title()),
            HistoryContentFormPage::make(
                $this->getItemID()
                    ? __('moonshine::ui.edit')
                    : __('moonshine::ui.add')
            ),
            HistoryContentDetailPage::make(__('moonshine::ui.show')),
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
