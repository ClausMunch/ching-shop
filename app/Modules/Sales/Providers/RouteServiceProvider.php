<?php

namespace ChingShop\Modules\Sales\Providers;

use Caffeinated\Modules\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Routing\Router;

/**
 * Class RouteServiceProvider
 *
 * @package ChingShop\Modules\Sales\Providers
 */
class RouteServiceProvider extends ServiceProvider
{
    /**
     * The controller namespace for the module.
     *
     * @var string|null
     */
    protected $namespace = 'ChingShop\Modules\Sales\Http\Controllers';

    /**
     * Define the routes for the module.
     *
     * @param  \Illuminate\Routing\Router $router
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
                require config('modules.path') . '/Sales/Http/routes.php';
            }
        );
    }
}
