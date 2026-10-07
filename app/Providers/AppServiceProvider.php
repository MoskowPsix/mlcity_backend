<?php

namespace App\Providers;

use App\Listeners\Notify\StartHoursListenersNotify;
use App\Mail\VpnSafeMailManager;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Resources\Json\JsonResource;
use MoonShine\MoonShine;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $loader = \Illuminate\Foundation\AliasLoader::getInstance();
        $loader->alias('Debugbar', \Barryvdh\Debugbar\Facades\Debugbar::class);
        $this->app->extend('mail.manager', function ($manager, $app) {
            return new VpnSafeMailManager($app);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        JsonResource::withoutWrapping();
        Event::listen(StartHoursListenersNotify::class);
    }
}
