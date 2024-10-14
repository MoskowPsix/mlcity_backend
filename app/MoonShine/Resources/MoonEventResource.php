<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\MoonEvent;
use App\MoonShine\Pages\MoonEvent\MoonEventIndexPage;
use App\MoonShine\Pages\MoonEvent\MoonEventFormPage;
use App\MoonShine\Pages\MoonEvent\MoonEventDetailPage;

use MoonShine\Resources\ModelResource;
use MoonShine\Pages\Page;

/**
 * @extends ModelResource<MoonEvent>
 */
class MoonEventResource extends ModelResource
{
    protected string $model = MoonEvent::class;

    protected string $title = 'MoonEvents';

    /**
     * @return list<Page>
     */
    public function pages(): array
    {
        return [
            MoonEventIndexPage::make($this->title()),
            MoonEventFormPage::make(
                $this->getItemID()
                    ? __('moonshine::ui.edit')
                    : __('moonshine::ui.add')
            ),
            MoonEventDetailPage::make(__('moonshine::ui.show')),
        ];
    }

    /**
     * @param MoonEvent $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    public function rules(Model $item): array
    {
        return [];
    }
}
