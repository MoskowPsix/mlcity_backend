<?php

namespace App\Providers;

use App\MoonShine\Resources\MoonEventResource;
use App\MoonShine\Resources\MoonRoleResource;
use App\MoonShine\Resources\MoonStatusResource;
use App\MoonShine\Resources\MoonTypeSightResource;
use App\MoonShine\Resources\MoonTypeEventResource;
use App\MoonShine\Resources\MoonUserResource;
use Illuminate\Support\ServiceProvider;
use MoonShine\MoonShine;
use MoonShine\Menu\MenuGroup;
use MoonShine\Menu\MenuItem;
use YuriZoom\MoonShineComposerViewer\Pages\ComposerViewerPage;
use YuriZoom\MoonShineLogViewer\Pages\LogViewerPage;
use YuriZoom\MoonShineMediaManager\Pages\MediaManagerPage;
use YuriZoom\MoonShineScheduling\Pages\SchedulingPage;

//use MoonShine\Resources\MoonSeanceResource;

class MoonShineServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        app(MoonShine::class)->menu([
            MenuGroup::make('moonshine::ui.resource.system', [
                MenuItem::make('Пользователи', new MoonUserResource())
                    ->translatable()
                    ->icon('heroicons.user-group'),
                MenuItem::make('Роли', new MoonRoleResource())
                    ->translatable()
                    ->icon('heroicons.lock-closed'),
                MenuItem::make(
                    static fn() => __('Команды по расписанию'),
                    new SchedulingPage(),
                ),
                MenuItem::make(
                    static fn() => __('Медиа менеджер'),
                    new MediaManagerPage(),
                ),
                MenuItem::make(
                    static fn() => __('Зависимости'),
                    new ComposerViewerPage(),
                ),
                MenuItem::make(
                    static fn() => __('Логи'),
                    new LogViewerPage(),
                ),
                // MenuItem::make('Сеансы', new ResourcesMoonSeanceResource())
                //     ->translatable()
                //                    ->icon('bookmark'),
            ])->translatable()->icon('heroicons.wrench-screwdriver'),
            MenuGroup::make('Контент', [
                MenuItem::make('События', new MoonEventResource())
                    ->translatable()
                    ->icon('heroicons.fire'),
                MenuItem::make('Статусы', new MoonStatusResource())
                    ->translatable()
                    ->icon('heroicons.clipboard-document-check'),
                MenuItem::make('Типы мест', new MoonTypeSightResource())
                    ->icon('heroicons.building-storefront'),
                MenuItem::make('Типы событий', new MoonTypeEventResource())
                    ->icon('heroicons.outline.cake')

            ])->icon('heroicons.rectangle-group'),
            //            MenuItem::make('Documentation', 'https://laravel.com')
            //                ->badge(fn() => 'Check'),
        ]);
    }
}
