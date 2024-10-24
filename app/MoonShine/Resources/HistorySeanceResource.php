<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\HistorySeance;
use Illuminate\Database\Eloquent\Model;
use App\Models\MoonHistorySeance;
use App\MoonShine\Pages\HistorySeance\HistorySeanceIndexPage;
use App\MoonShine\Pages\HistorySeance\HistorySeanceFormPage;
use App\MoonShine\Pages\HistorySeance\HistorySeanceDetailPage;

use MoonShine\Resources\ModelResource;
use MoonShine\Pages\Page;

/**
 * @extends ModelResource<HistorySeance>
 */
class HistorySeanceResource extends ModelResource
{
    protected string $model = HistorySeance::class;

    protected string $title = 'MoonHistorySeances';

    /**
     * @return list<Page>
     */
    public function pages(): array
    {
        return [
            HistorySeanceIndexPage::make($this->title()),
            HistorySeanceFormPage::make(
                $this->getItemID()
                    ? __('moonshine::ui.edit')
                    : __('moonshine::ui.add')
            ),
            HistorySeanceDetailPage::make(__('moonshine::ui.show')),
        ];
    }

    /**
     * @param HistorySeance $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    public function rules(Model $item): array
    {
        return [];
    }
}
