<?php

namespace ChingShop\Modules\Shipping\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as Provider;
use Illuminate\Routing\Router;

/**
 * Class RouteServiceProvider.
 */
class RouteServiceProvider extends Provider
{
    /**
     * The controller namespace for the module.
     *
     * @var string|null
     */
    protected $namespace = 'ChingShop\Modules\Shipping\Http\Controllers';

    /**
     * Define the routes for the module.
     *
     * @param \Illuminate\Routing\Router $router
     *
     * @return void
     */
    public function map(Router $router)
    {
        $router->group(
            [
                'namespace'  => $this->namespace,
                'middleware' => ['web'],
            ],
            function () {
                /** @noinspection PhpIncludeInspection */
                require config('modules.path').'/Shipping/Http/routes.php';
            }
        );
    }
}
