<?php

namespace App\Providers;

use App\Events\Event\EventCreated;
use App\Events\Place\PlaceCreated;
use App\Events\Sight\SightCreated;
use App\Listeners\Event\CreateHistoryContent;
use App\Listeners\Place\CreateHistoryPlace;
use App\Listeners\Sight\CreateHistoryContent as SightCreateHistoryContent;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        \SocialiteProviders\Manager\SocialiteWasCalled::class => [
            // ... other providers
            \SocialiteProviders\VKontakte\VKontakteExtendSocialite::class.'@handle',
            \SocialiteProviders\Telegram\TelegramExtendSocialite::class.'@handle',
            \SocialiteProviders\Apple\AppleExtendSocialite::class.'@handle',
        ],

        EventCreated::class => [
            CreateHistoryContent::class
        ],

        SightCreated::class => [
            SightCreateHistoryContent::class
        ]

        
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
