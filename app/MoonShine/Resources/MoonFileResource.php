<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\EventFile;
use Illuminate\Database\Eloquent\Model;
use App\MoonShine\Pages\MoonFile\MoonFileIndexPage;
use App\MoonShine\Pages\MoonFile\MoonFileFormPage;
use App\MoonShine\Pages\MoonFile\MoonFileDetailPage;

use MoonShine\Fields\Checkbox;
use MoonShine\Fields\ID;
use MoonShine\Fields\Image;
use MoonShine\Fields\Text;
use MoonShine\Resources\ModelResource;
use MoonShine\Pages\Page;

/**
 * @extends ModelResource<EventFile>
 */
class MoonFileResource extends ModelResource
{
    protected string $model = EventFile::class;

    protected string $title = 'Файлы';

    public function fields(): array
    {
        return [
//            ID::make(),
//            Text::make('Название', 'name'),
            $this->showImage(),
//            Checkbox::make('Локально', 'local')
        ];
    }

    /**
     * @return list<Page>
     */
    public function pages(): array
    {
        return [
            MoonFileIndexPage::make($this->title()),
            MoonFileFormPage::make(
                $this->getItemID()
                    ? __('moonshine::ui.edit')
                    : __('moonshine::ui.add')
            ),
            MoonFileDetailPage::make(__('moonshine::ui.show')),
        ];
    }

    /**
     * @param EventFile $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    public function rules(Model $item): array
    {
        return [];
    }
    private function showImage(): Image
    {
        $url = env('APP_URL');
        return Image::make('Фото', 'link')
            ->changePreview(function ($data) use ($url) {
                if (substr($data, 0, 4) == 'http') {
                    return view('moonshine::ui.image', ['value' => $data]);
                } else if (substr($data, 0, 4) == '/sto') {
                    return view('moonshine::ui.image', ['value' => $url . $data]);
                } else {
                    return $data;
                }
            });
    }
}
