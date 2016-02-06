<?php

namespace ChingShop\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use ChingShop\Http\View\Customer\LocationComposer;

class Customer
{
    /**
     * Handle an incoming request.
     * @param  Request  $request
     * @param  \Closure  $next
     * @return Response
     */
    public function handle(Request $request, Closure $next)
    {
        view()->composer('*', LocationComposer::class);
        return $next($request);
    }
}
