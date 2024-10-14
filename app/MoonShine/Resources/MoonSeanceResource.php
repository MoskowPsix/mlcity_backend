<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\MoonSeance;
use App\Models\Seance;
use App\MoonShine\Pages\MoonSeance\MoonSeanceIndexPage;
use App\MoonShine\Pages\MoonSeance\MoonSeanceFormPage;
use App\MoonShine\Pages\MoonSeance\MoonSeanceDetailPage;

use MoonShine\Fields\ID;
use MoonShine\Fields\Date;
use MoonShine\Resources\ModelResource;
use MoonShine\Pages\Page;

/**
 * @extends ModelResource<MoonSeance>
 */
class MoonSeanceResource extends ModelResource
{
    protected string $model = Seance::class;

    protected string $title = 'Сеансы';

    public function fields(): array
    {
        return [
            ID::make()->sortable(),
            Date::make('Дата начала', 'date_start')->format('d.m.Y H:i'),
            Date::make('Дата конца', 'date_end')->format('d.m.Y H:i'),
        ];
    }

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
