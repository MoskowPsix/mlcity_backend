<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\Price;
use Illuminate\Database\Eloquent\Model;
use App\MoonShine\Pages\Price\PriceIndexPage;
use App\MoonShine\Pages\Price\PriceFormPage;
use App\MoonShine\Pages\Price\PriceDetailPage;

use MoonShine\Resources\ModelResource;
use MoonShine\Pages\Page;

/**
 * @extends ModelResource<Price>
 */
class PriceResource extends ModelResource
{
    protected string $model = Price::class;

    protected string $title = 'Цены';

    protected string $column = 'cost_rub';

    /**
     * @return list<Page>
     */
    public function pages(): array
    {
        return [
            PriceIndexPage::make($this->title()),
            PriceFormPage::make(
                $this->getItemID()
                    ? __('moonshine::ui.edit')
                    : __('moonshine::ui.add')
            ),
            PriceDetailPage::make(__('moonshine::ui.show')),
        ];
    }

    /**
     * @param Price $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    public function rules(Model $item): array
    {
        return [];
    }
}
