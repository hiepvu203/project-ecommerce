<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $policies = [
        \App\Models\Shop::class => \App\Policies\ShopPolicy::class,
        \App\Models\Product::class => \App\Policies\ProductPolicy::class,
    ];
}
