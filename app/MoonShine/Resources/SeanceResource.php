<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\MoonSeance;
use App\Models\Seance;
use App\MoonShine\Pages\Seance\SeanceIndexPage;
use App\MoonShine\Pages\Seance\SeanceFormPage;
use App\MoonShine\Pages\Seance\SeanceDetailPage;

use MoonShine\Fields\ID;
use MoonShine\Fields\Date;
use MoonShine\Resources\ModelResource;
use MoonShine\Pages\Page;

/**
 * @extends ModelResource<Seance>
 */
class SeanceResource extends ModelResource
{
    protected string $model = Seance::class;

    protected string $title = 'Сеансы';

    /**
     * @return list<Page>
     */
    public function pages(): array
    {
        return [
            SeanceIndexPage::make($this->title()),
            SeanceFormPage::make(
                $this->getItemID()
                    ? __('moonshine::ui.edit')
                    : __('moonshine::ui.add')
            ),
            SeanceDetailPage::make(__('moonshine::ui.show')),
        ];
    }

    /**
     * @param Seance $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    public function rules(Model $item): array
    {
        return [];
    }
}
