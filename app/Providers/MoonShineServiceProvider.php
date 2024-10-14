<?php

namespace App\Providers;

use App\MoonShine\Resources\MoonRoleResource;
use App\MoonShine\Resources\MoonUserResource;
use Illuminate\Support\ServiceProvider;
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
                MenuItem::make('moonshine::ui.resource.role_title', new MoonRoleResource())
                    ->translatable()
//                    ->icon('bookmark'),
            ])->translatable(),

            MenuItem::make('Documentation', 'https://laravel.com')
                ->badge(fn() => 'Check'),
        ]);
    }
}
