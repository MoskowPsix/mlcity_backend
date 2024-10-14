<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\SocialAccount;
use Illuminate\Database\Eloquent\Model;
use App\Models\MoonSocialAccount;

use MoonShine\Fields\Date;
use MoonShine\Fields\Number;
use MoonShine\Fields\Text;
use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Fields\ID;
use MoonShine\Fields\Field;
use MoonShine\Components\MoonShineComponent;

/**
 * @extends ModelResource<SocialAccount>
 */
class MoonSocialAccountResource extends ModelResource
{
    protected string $model = SocialAccount::class;

    protected string $title = 'MoonSocialAccounts';

    protected string $column = 'provider';

    /**
     * @return list<MoonShineComponent|Field>
     */
    public function fields(): array
    {
        return [
            Block::make([
                ID::make()->sortable(),
                Text::make('Соц.сеть', 'provider'),
                Date::make('Создано', 'created_at'),
                Date::make('Обновлено', 'updated_at')
            ]),
        ];
    }

    /**
     * @param SocialAccount $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    public function rules(Model $item): array
    {
        return [];
    }
}
