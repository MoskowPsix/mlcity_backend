<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\MoonSeance;
use App\MoonShine\Pages\MoonSeance\MoonSeanceIndexPage;
use App\MoonShine\Pages\MoonSeance\MoonSeanceFormPage;
use App\MoonShine\Pages\MoonSeance\MoonSeanceDetailPage;

use MoonShine\Resources\ModelResource;
use MoonShine\Pages\Page;

/**
 * @extends ModelResource<MoonSeance>
 */
class MoonSeanceResource extends ModelResource
{
    protected string $model = MoonSeance::class;

    protected string $title = 'MoonSeances';

    /**
     * @return list<Page>
     */
    public function pages(): array
    {
        return [
            MoonSeanceIndexPage::make($this->title()),
            MoonSeanceFormPage::make(
                $this->getItemID()
                    ? __('moonshine::ui.edit')
                    : __('moonshine::ui.add')
            ),
            MoonSeanceDetailPage::make(__('moonshine::ui.show')),
        ];
    }

    /**
     * @param MoonSeance $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    public function rules(Model $item): array
    {
        return [];
    }
}
