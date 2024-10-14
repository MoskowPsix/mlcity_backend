<?php

namespace App\Providers;

use App\MoonShine\Resources\MoonEventResource;
use App\MoonShine\Resources\MoonRoleResource;
use App\MoonShine\Resources\MoonUserResource;
use Illuminate\Support\ServiceProvider;
use Laravel\Telescope\TelescopeApplicationServiceProvider;
use MoonShine\MoonShine;
use MoonShine\Menu\MenuGroup;
use MoonShine\Menu\MenuItem;
use MoonShine\Resources\MoonShineUserResource;
use MoonShine\Resources\MoonShineUserRoleResource;

class MoonShineServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        app(MoonShine::class)->menu([
            MenuGroup::make('moonshine::ui.resource.system', [
                MenuItem::make('Пользователи', new MoonUserResource())
                    ->translatable(),
//                    ->icon('users'),
                MenuItem::make('Роли', new MoonRoleResource())
                    ->translatable(),
//                    ->icon('bookmark'),
            ])->translatable(),
            MenuGroup::make('Контент', [
                MenuItem::make('События', new MoonEventResource())
                    ->translatable()
                ]),

//            MenuItem::make('Documentation', 'https://laravel.com')
//                ->badge(fn() => 'Check'),
        ]);
    }
}
