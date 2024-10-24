<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\Organization;
use Illuminate\Database\Eloquent\Model;
use App\MoonShine\Pages\Organization\OrganizationIndexPage;
use App\MoonShine\Pages\Organization\OrganizationFormPage;
use App\MoonShine\Pages\Organization\OrganizationDetailPage;

use MoonShine\Resources\ModelResource;
use MoonShine\Pages\Page;

/**
 * @extends ModelResource<Organization>
 */
class OrganizationResource extends ModelResource
{
    protected string $model = Organization::class;

    protected string $title = 'Организации';

    /**
     * @return list<Page>
     */
    public function pages(): array
    {
        return [
            OrganizationIndexPage::make($this->title()),
            OrganizationFormPage::make(
                $this->getItemID()
                    ? __('moonshine::ui.edit')
                    : __('moonshine::ui.add')
            ),
            OrganizationDetailPage::make(__('moonshine::ui.show')),
        ];
    }

    /**
     * @param Organization $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    public function rules(Model $item): array
    {
        return [];
    }
}
