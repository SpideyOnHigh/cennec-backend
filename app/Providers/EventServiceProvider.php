<?php

namespace App\Providers;

use App\Events\SendEmailEvent;
use App\Events\SMSLogsEvent;
use App\Events\SuccessfulLogin;
use App\Events\TicketLogs;
use App\Listeners\LogSuccessfulLogin;
use App\Listeners\SendEmailListener;
use App\Listeners\SMSLogsListener;
use App\Listeners\TicketLogsListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

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
        TicketLogs::class => [
            TicketLogsListener::class,
        ],
        SendEmailEvent::class => [
            SendEmailListener::class,
        ],
        SMSLogsEvent::class => [
            SMSLogsListener::class,
        ],
        SuccessfulLogin::class => [
            LogSuccessfulLogin::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
