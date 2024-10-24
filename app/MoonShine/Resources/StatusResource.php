<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\Status;
use Illuminate\Database\Eloquent\Model;
use App\Models\MoonStatus;

use MoonShine\Fields\Text;
use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Fields\ID;
use MoonShine\Fields\Field;
use MoonShine\Components\MoonShineComponent;

/**
 * @extends ModelResource<Status>
 */
class StatusResource extends ModelResource
{
    protected string $model = Status::class;

    protected string $title = 'Статус';

    protected string $column = 'name';
    protected bool $simplePaginate = true;
    protected bool $isAsync = true;
    public static array $activeActions = ['view'];

    public function getActiveActions(): array
    {
        if (auth()->id() === $this->getItem()?->author_id) {
            return static::$activeActions;
        }

        return static::$activeActions;
    }

    public function search(): array
    {
        return ['id', 'name'];
    }

    /**
     * @return list<MoonShineComponent|Field>
     */
    public function fields(): array
    {
        return [
            Block::make([
                ID::make()->sortable(),
                Text::make('Название', 'name')
            ]),
        ];
    }

    /**
     * @param Status $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    public function rules(Model $item): array
    {
        return [];
    }
}
