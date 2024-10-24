<?php

namespace App\Providers;

use App\MoonShine\Pages\HistoryPlace\HistoryPlaceDetailPage;
use App\MoonShine\Resources\EventResource;
use App\MoonShine\Resources\HistoryPlaceResource;
use App\MoonShine\Resources\HistorySeanceResource;
use App\MoonShine\Resources\LocationResource;
use App\MoonShine\Resources\OrganizationResource;
use App\MoonShine\Resources\PlaceResource;
use App\MoonShine\Resources\RoleResource;
use App\MoonShine\Resources\SeanceResource;
use App\MoonShine\Resources\SightResource;
use App\MoonShine\Resources\StatusResource;
use App\MoonShine\Resources\SightTypeResource;
use App\MoonShine\Resources\EventTypeResource;
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
            new OrganizationResource(),
            new PlaceResource(),
            new HistoryPlaceResource(),
            new SeanceResource(),
            new HistorySeanceResource(),
            new LocationResource(),
        ];
    }
    protected function menu(): array
    {
        return [
            MenuGroup::make('moonshine::ui.resource.system', [
                MenuItem::make('Пользователи', new MoonUserResource())
                    ->translatable()
                    ->icon('heroicons.user-group'),
                MenuItem::make('Роли', new RoleResource())
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
                MenuItem::make('Telescope', '/telescope')
            ])->translatable()->icon('heroicons.wrench-screwdriver'),
            MenuGroup::make('Контент', [
                MenuItem::make('События', new EventResource())
                    ->translatable()
                    ->icon('heroicons.fire'),
                MenuItem::make('Места', new SightResource())
                    ->translatable()
                    ->icon('heroicons.flag'),
                MenuItem::make('Города', new LocationResource())
                    ->translatable()
                    ->icon('heroicons.building-office-2'),
                MenuItem::make('Статусы', new StatusResource())
                    ->translatable()
                    ->icon('heroicons.clipboard-document-check'),
                MenuItem::make('Типы мест', new SightTypeResource())
                    ->icon('heroicons.building-storefront'),
                MenuItem::make('Типы событий', new EventTypeResource())
                    ->icon('heroicons.outline.cake'),
            ])->icon('heroicons.rectangle-group'),
        ];
    }
}
