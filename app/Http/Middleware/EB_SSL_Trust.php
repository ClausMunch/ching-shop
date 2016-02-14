<?php

namespace ChingShop\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Class EB_SSL_Trust
 * @package ChingShop\Http\Middleware
 * Always trust X-Forwarded-For...
 */
class EB_SSL_Trust
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $request->setTrustedProxies([$request->getClientIp()]);
        return $next($request);
    }
}
