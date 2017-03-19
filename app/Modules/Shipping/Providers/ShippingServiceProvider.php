<?php

namespace ChingShop\Modules\Shipping\Providers;

use App;
use Illuminate\Support\ServiceProvider;
use View;

/**
 * Class ShippingServiceProvider.
 */
class ShippingServiceProvider extends ServiceProvider
{
    /**
     * Register the Shipping module service provider.
     *
     * This service provider is a convenient place to register your modules
     * services in the IoC container. If you wish, you may make additional
     * methods or service providers to keep the code more focused and granular.
     *
     * @return void
     */
    public function register()
    {
        App::register(RouteServiceProvider::class);

        /* @noinspection RealpathOnRelativePathsInspection */
        View::addNamespace('shipping', realpath(__DIR__.'/../Resources/Views'));
    }

    /**
     * Bootstrap the application events.
     *
     * Here you may register any additional middleware provided with your
     * module with the following addMiddleware() method. You may pass in
     * either an array or a string.
     *
     * @return void
     */
    public function boot()
    {
    }
}
