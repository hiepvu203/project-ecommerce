<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        // \App\Events\UserRegistered::class => [
        //     \App\Listeners\CreateUserCart::class,
        //     \App\Listeners\CreateUserProfile::class,
        // ],
    ];
}
