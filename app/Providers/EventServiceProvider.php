<?php

namespace ChingShop\Providers;

use ChingShop\Events\NewImageEvent;
use ChingShop\Listeners\NewImageListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as Provider;

class EventServiceProvider extends Provider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        NewImageEvent::class => [
            NewImageListener::class,
        ],
    ];
}
