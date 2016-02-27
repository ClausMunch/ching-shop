<?php

namespace ChingShop\Providers;

use ChingShop\Http\View\ReplyComposer;
use Illuminate\Support\ServiceProvider;
use Laracasts\Generators\GeneratorsServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
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
