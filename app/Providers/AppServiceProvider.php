<?php

namespace ChingShop\Providers;

use Illuminate\Support\ServiceProvider;

use Laracasts\Generators\GeneratorsServiceProvider;

use ChingShop\Http\View\LocationComposer;
use ChingShop\Http\View\ReplyComposer;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('*', LocationComposer::class);
        view()->composer('*', ReplyComposer::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment() == 'local') {
            $this->app->register(GeneratorsServiceProvider::class);
        }

        $this->app->singleton(
            \ChingShop\Validation\ValidationInterface::class,
            \ChingShop\Validation\IlluminateValidation::class
        );
    }
}
