<?php

namespace App\Providers;

use App\Contracts\Services\EventService\EventService;
use App\Contracts\Services\EventService\EventServiceInterface;
use App\Contracts\Services\FileService\FileService;
use App\Contracts\Services\OrganizationService\OrganizationService;
use Illuminate\Support\ServiceProvider;

class ServiceServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(
            EventServiceInterface::class,
            EventService::class
        );

        $this->app->bind(
            FileService::class
        );

        $this->app->bind(
            OrganizationService::class
        );
    }
}
