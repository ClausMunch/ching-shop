<?php

namespace ChingShop\Providers;

use App;
use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;
use ChingShop\Http\View\ReplyComposer;
use ChingShop\Validation\IlluminateValidation;
use ChingShop\Validation\ValidationInterface;
use Illuminate\Support\ServiceProvider;
use Laracasts\Generators\GeneratorsServiceProvider;
use Pvm\ArtisanBeans\ArtisanBeansServiceProvider;

/**
 * Class AppServiceProvider.
 */
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
        if ($this->app->environment() === 'local') {
            $this->app->register(GeneratorsServiceProvider::class);
        }

        $this->app->singleton(
            ValidationInterface::class,
            IlluminateValidation::class
        );

        if ($this->app->environment() === 'local' && \App::runningInConsole()) {
            $this->app->register(ArtisanBeansServiceProvider::class);
            $this->app->register(IdeHelperServiceProvider::class);
        }
    }
}
