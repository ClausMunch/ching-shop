<?php

namespace ChingShop\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as Provider;
use Illuminate\Routing\Router;

/**
 * Class RouteServiceProvider
 *
 * @package ChingShop\Providers
 */
class RouteServiceProvider extends Provider
{
    /**
     * This namespace is applied to the controller routes in your routes file.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'ChingShop\Http\Controllers';

    /**
     * Define the routes for the application.
     *
     * @param \Illuminate\Routing\Router $router
     *
     * @return void
     */
    public function map(Router $router)
    {
        $router->group(
            ['namespace' => $this->namespace],
            function () {
                /** @noinspection PhpIncludeInspection */
                require app_path('Http/routes.php');
            }
        );
    }
}
