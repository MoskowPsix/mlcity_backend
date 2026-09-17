<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\CategorySuggestion;
use Illuminate\Database\Eloquent\Model;
use MoonShine\Components\MoonShineComponent;
use MoonShine\Decorations\Block;
use MoonShine\Enums\ClickAction;
use MoonShine\Fields\Date;
use MoonShine\Fields\Field;
use MoonShine\Fields\ID;
use MoonShine\Fields\Text;
use MoonShine\Fields\Textarea;
use MoonShine\Resources\ModelResource;

/**
 * @extends ModelResource<CategorySuggestion>
 */
class CategorySuggestionResource extends ModelResource
{
    protected string $model = CategorySuggestion::class;

    protected string $title = 'Предложения категорий';

    protected string $column = 'name';

    protected bool $simplePaginate = true;

    protected bool $isAsync = true;

    public static array $activeActions = ['view', 'delete'];

    protected ?ClickAction $clickAction = ClickAction::DETAIL;

    public function search(): array
    {
        return ['id', 'name', 'description'];
    }

    public function getBadge(): int|false
    {
        $count = CategorySuggestion::query()->count();

        return $count > 0 ? $count : false;
    }

    /**
     * @return list<MoonShineComponent|Field>
     */
    public function indexFields(): array
    {
        return [
            ID::make()->sortable(),
            Text::make('Название', 'name')->sortable(),
            Text::make('Описание', 'description'),
            Date::make('Создано', 'created_at')->format('d.m.Y H:i')->sortable(),
        ];
    }

    public function detailFields(): array
    {
        return [
            ID::make(),
            Text::make('Название', 'name'),
            Textarea::make('Описание', 'description'),
            Date::make('Создано', 'created_at')->format('d.m.Y H:i'),
        ];
    }

    public function formFields(): array
    {
        return [
            Block::make([
                Text::make('Название', 'name')->required(),
                Textarea::make('Описание', 'description')->required(),
            ]),
        ];
    }

    /**
     * @param CategorySuggestion $item
     *
     * @return array<string, string[]|string>
     */
    public function rules(Model $item): array
    {
        return [];
    }
}
