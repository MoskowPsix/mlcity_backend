<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use App\MoonShine\Pages\User\UserIndexPage;
use App\MoonShine\Pages\User\UserFormPage;
use App\MoonShine\Pages\User\UserDetailPage;
use Illuminate\Support\Facades\Storage;
use MoonShine\Components\Badge;
use MoonShine\Components\Boolean;
use MoonShine\Components\Link;
use MoonShine\Decorations\Column;
use MoonShine\Decorations\Grid;
use MoonShine\Enums\ClickAction;
use MoonShine\Fields\Checkbox;
use MoonShine\Fields\Field;

use MoonShine\Fields\File;
use MoonShine\Fields\ID;
use MoonShine\Fields\Image;
use MoonShine\Fields\Relationships\BelongsToMany;
use MoonShine\Fields\Relationships\HasOne;
use MoonShine\Fields\Text;
use MoonShine\Resources\ModelResource;
use MoonShine\Pages\Page;

/**
 * @extends ModelResource<User>
 */
class MoonUserResource extends ModelResource
{
    protected string $model = User::class;

    protected string $title = 'Пользователи';
    protected string $column = 'name';
    protected bool $simplePaginate = true;
    protected bool $isAsync = true;
    protected ?ClickAction $clickAction = ClickAction::DETAIL;
    public function fields(): array
    {
        return [
            ID::make()->sortable(),
            Text::make('Имя', 'name')->sortable(),
            Text::make('Email', 'email')->sortable(),
            Image::make('Аватар', 'avatar')
        ];
    }
    public function indexFields(): array
    {
        $url = env('APP_URL');
        return [
            ID::make()->sortable(),
            Text::make('Имя', 'name')->sortable(),
            Text::make('Email', 'email')->sortable(),
            Image::make('Аватар', 'avatar')
                ->changePreview(function ($data) use ($url) {
                    if (empty($data)) {
                        return '';
                    }

                    if (substr($data, 0, 4) == 'http') {
                        return view('moonshine::ui.image', ['value' => $data]);
                    } else if (substr($data, 0, 4) == '/sto') {
                        return view('moonshine::ui.image', ['value' => $url . $data]);
                    } else {
                        return $data;
                    }
                }),
            BelongsToMany::make('Роли', 'roles', resource: new RoleResource())
                ->inLine(
                    separator: ' ',
                    link: fn(Role $role, $value, $field) => Link::make(
                        (new RoleResource())->detailPageUrl($role),
                        $value
                    )
                ),
        ];
    }
    public function detailFields(): array
    {
        $url = env('APP_URL');
        return [
            ID::make()->sortable(),
            Text::make('Имя', 'name'),
            Text::make('Email', 'email'),
            Checkbox::make('Верфикация почты', 'email_verified_at'),
            Image::make('Аватар', 'avatar')
                ->changePreview(function ($data) use ($url) {
                    if (substr($data, 0, 4) == 'http') {
                        return view('moonshine::ui.image', ['value' => $data]);
                    } else if (substr($data, 0, 4) == '/sto') {
                        return view('moonshine::ui.image', ['value' => $url . $data]);
                    } else {
                        return $data;
                    }
                }),
            HasOne::make('Социальная сеть', 'socialAccount', resource: new SocialAccountResource()),
            BelongsToMany::make('Роли', 'roles', resource: new RoleResource())
                ->inLine(
                    separator: ' ',
                    link: fn(Role $role, $value, $field) => Link::make(
                        (new RoleResource())->detailPageUrl($role),
                        $value
                    )
                ),
        ];
    }

    public function formFields(): array
    {
        $url = env('APP_URL');
        return [
            ID::make()->sortable(),
            Text::make('Имя', 'name')->sortable(),
            Text::make('Email', 'email')->sortable(),
            Checkbox::make('Верфикация почты', 'email_verified_at'),
            BelongsToMany::make('Роль', 'roles', resource: new RoleResource())->selectMode(),
            File::make('Аватар', 'avatar')
                ->onApply(function (Model $item, $value, Field $field) {
                    $storage = 'public';
                    $puth = "users/$item->id";
                    if ($value) {
                        $save = Storage::disk($storage)->put($puth, $value);
                        $item->avatar = "/storage/$save";
                    }
                    return $item;
                })
                ->changeFill(function () use ($url) {
                    return '';
                })
        ];
    }
    /**
     * @return list<Page>
     */
    public function pages(): array
    {
        return [
            UserIndexPage::make($this->title()),
            UserFormPage::make(
                $this->getItemID()
                    ? __('moonshine::ui.edit')
                    : __('moonshine::ui.add')
            ),
            UserDetailPage::make(__('moonshine::ui.show')),
        ];
    }

    /**
     * @param User $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    public function rules(Model $item): array
    {
        return [];
    }
}
