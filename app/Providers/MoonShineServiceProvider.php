<?php

namespace App\Providers;

use App\MoonShine\Pages\MoonEventPage;
use App\MoonShine\Resources\MoonEventResource;
use App\MoonShine\Resources\MoonLocationResource;
use App\MoonShine\Resources\MoonOrganizationResource;
use App\MoonShine\Resources\MoonPlaceResource;
use App\MoonShine\Resources\MoonRoleResource;
use App\MoonShine\Resources\MoonSightResource;
use App\MoonShine\Resources\MoonStatusResource;
use App\MoonShine\Resources\MoonUserResource;
use Illuminate\Support\ServiceProvider;
use MoonShine\MoonShine;
use MoonShine\Menu\MenuGroup;
use MoonShine\Menu\MenuItem;
use MoonShine\Providers\MoonShineApplicationServiceProvider;
use YuriZoom\MoonShineComposerViewer\Pages\ComposerViewerPage;
use YuriZoom\MoonShineLogViewer\Pages\LogViewerPage;
use YuriZoom\MoonShineMediaManager\Pages\MediaManagerPage;
use YuriZoom\MoonShineScheduling\Pages\SchedulingPage;

//use MoonShine\Resources\MoonSeanceResource;

class MoonShineServiceProvider extends MoonShineApplicationServiceProvider
{
    protected function resources(): array
    {
        return [
            new MoonOrganizationResource(),
        ];
    }
    protected function menu(): array
    {
        return [
            MenuGroup::make('moonshine::ui.resource.system', [
                MenuItem::make('Пользователи', new MoonUserResource())
                    ->translatable()
                    ->icon('heroicons.user-group'),
                MenuItem::make('Роли', new MoonRoleResource())
                    ->translatable()
                    ->icon('heroicons.lock-closed'),
                MenuItem::make(
                    static fn () => __('Команды по расписанию'),
                    new SchedulingPage(),
                ),
                MenuItem::make(
                    static fn () => __('Медиа менеджер'),
                    new MediaManagerPage(),
                ),
                MenuItem::make(
                    static fn () => __('Зависимости'),
                    new ComposerViewerPage(),
                ),
                MenuItem::make(
                    static fn () => __('Логи'),
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
                MenuItem::make('Места', new MoonSightResource())
                    ->translatable()
                    ->icon('heroicons.flag'),
//                MenuItem::make('Place', new MoonPlaceResource())
//                    ->translatable()
//                    ->icon('heroicons.flag'),
                MenuItem::make('Города', new MoonLocationResource())
                    ->translatable()
                    ->icon('heroicons.building-office-2'),
            ])->icon('heroicons.rectangle-group'),
//            MenuItem::make('Documentation', 'https://laravel.com')
//                ->badge(fn() => 'Check'),
        ];
    }
}
