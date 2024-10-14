<?php

namespace App\Providers;

use App\MoonShine\Resources\MoonEventResource;
use App\MoonShine\Resources\MoonRoleResource;
use App\MoonShine\Resources\MoonSeanceResource as ResourcesMoonSeanceResource;
use App\MoonShine\Resources\MoonUserResource;
use Illuminate\Support\ServiceProvider;
use MoonShine\MoonShine;
use MoonShine\Menu\MenuGroup;
use MoonShine\Menu\MenuItem;
use MoonShine\Resources\MoonShineUserResource;
use MoonShine\Resources\MoonShineUserRoleResource;
//use MoonShine\Resources\MoonSeanceResource;

class MoonShineServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        app(MoonShine::class)->menu([
            MenuGroup::make('moonshine::ui.resource.system', [
                MenuItem::make('Пользователи', new MoonUserResource())
                    ->translatable(),
                MenuItem::make('Роли', new MoonRoleResource())
                    ->translatable(),
                // MenuItem::make('Сеансы', new ResourcesMoonSeanceResource())
                //     ->translatable()
                //                    ->icon('bookmark'),
            ])->translatable(),
            MenuGroup::make('Контент', [
                MenuItem::make('События', new MoonEventResource())
                    ->translatable()
                ]),

            MenuItem::make('Documentation', 'https://laravel.com')
                ->badge(fn() => 'Check'),
        ]);
    }
}
