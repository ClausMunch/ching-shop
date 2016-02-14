<?php

namespace ChingShop\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
    ];

    /**
     * The application's route middleware groups.
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            Middleware\EB_SSL_Trust::class,
            Middleware\ForceSecure::class,
            Middleware\EncryptCookies::class,

            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,

            Middleware\VerifyCsrfToken::class,

            \GrahamCampbell\HTMLMin\Http\Middleware\MinifyMiddleware::class,
        ],
        'api' => [
            Middleware\EB_SSL_Trust::class,
            Middleware\ForceSecure::class,
            'throttle:60,1',
            'auth:api',
        ],
    ];

    /**
     * The application's route middleware.
     * @var array
     */
    protected $routeMiddleware = [
        'auth'       => Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'guest'      => Middleware\RedirectIfAuthenticated::class,
        'staff'      => Middleware\StaffOnly::class,
        'customer'   => Middleware\Customer::class,
    ];
}
