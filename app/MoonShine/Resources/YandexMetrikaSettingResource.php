<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\YandexMetrikaSetting;
use Illuminate\Database\Eloquent\Model;
use MoonShine\Decorations\Block;
use MoonShine\Fields\Checkbox;
use MoonShine\Fields\ID;
use MoonShine\Fields\Text;
use MoonShine\Resources\ModelResource;

/**
 * @extends ModelResource<YandexMetrikaSetting>
 */
class YandexMetrikaSettingResource extends ModelResource
{
    protected string $model = YandexMetrikaSetting::class;

    protected string $title = 'Яндекс Метрика';

    protected string $column = 'counter_id';

    public static array $activeActions = ['view', 'update'];

    public function indexFields(): array
    {
        return [
            ID::make()->sortable(),
            Text::make('Номер счётчика', 'counter_id'),
            Checkbox::make('Включена', 'enabled'),
            Checkbox::make('Вебвизор', 'webvisor'),
        ];
    }

    public function detailFields(): array
    {
        return $this->formFields();
    }

    public function formFields(): array
    {
        return [
            Block::make([
                Text::make('Номер счётчика', 'counter_id')
                    ->hint('Число из кабинета Метрики. Фронт подхватит без пересборки.'),
                Checkbox::make('Включена', 'enabled'),
                Checkbox::make('Вебвизор', 'webvisor'),
                Checkbox::make('Карта кликов', 'clickmap'),
                Checkbox::make('Отслеживание ссылок', 'track_links'),
                Checkbox::make('Точный показатель отказов', 'accurate_track_bounce'),
            ]),
        ];
    }

    public function rules(Model $item): array
    {
        return [
            'counter_id' => ['nullable', 'regex:/^\d+$/'],
        ];
    }
}
