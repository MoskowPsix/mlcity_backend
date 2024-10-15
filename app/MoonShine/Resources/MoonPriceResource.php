<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\Price;
use Illuminate\Database\Eloquent\Model;
use App\MoonShine\Pages\MoonPrice\MoonPriceIndexPage;
use App\MoonShine\Pages\MoonPrice\MoonPriceFormPage;
use App\MoonShine\Pages\MoonPrice\MoonPriceDetailPage;

use MoonShine\Resources\ModelResource;
use MoonShine\Pages\Page;

/**
 * @extends ModelResource<Price>
 */
class MoonPriceResource extends ModelResource
{
    protected string $model = Price::class;

    protected string $title = 'Цены';

    /**
     * @return list<Page>
     */
    public function pages(): array
    {
        return [
            MoonPriceIndexPage::make($this->title()),
            MoonPriceFormPage::make(
                $this->getItemID()
                    ? __('moonshine::ui.edit')
                    : __('moonshine::ui.add')
            ),
            MoonPriceDetailPage::make(__('moonshine::ui.show')),
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
