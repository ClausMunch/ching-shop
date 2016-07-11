<?php

namespace ChingShop\Http\Middleware;

use ChingShop\Http\View\Customer\LocationComposer;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class Customer
 *
 * @package ChingShop\Http\Middleware
 */
class Customer
{
    /**
     * Handle an incoming request.
     *
     * @param Request  $request
     * @param \Closure $next
     *
     * @return Response
     */
    public function handle(Request $request, Closure $next)
    {
        view()->composer('*', LocationComposer::class);

        return $next($request);
    }
}
