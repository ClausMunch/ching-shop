<?php

namespace ChingShop\Modules\User\Providers;

use App;
use Caffeinated\Modules\Support\ServiceProvider;
use Lang;
use View;

/**
 * Class UserServiceProvider.
 */
class UserServiceProvider extends ServiceProvider
{
    /**
     * Additional Compiled Module Classes.
     *
     * Here you may specify additional classes to include in the compiled file
     * generated by the `artisan optimize` command. These should be classes
     * that are included on basically every request into the application.
     *
     * @return array
     */
    public static function compiles()
    {
        return [];
    }

    /**
     * Register the User module service provider.
     *
     * This service provider is a convenient place to register your modules
     * services in the IoC container. If you wish, you may make additional
     * methods or service providers to keep the code more focused and granular.
     *
     * @return void
     */
    public function register()
    {
        App::register('ChingShop\Modules\User\Providers\RouteServiceProvider');

        /* @noinspection RealpathOnRelativePathsInspection */
        Lang::addNamespace('user', realpath(__DIR__.'/../Resources/Lang'));
        View::addNamespace('user', base_path('resources/views/vendor/user'));
        /* @noinspection RealpathOnRelativePathsInspection */
        View::addNamespace('user', realpath(__DIR__.'/../Resources/Views'));
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
