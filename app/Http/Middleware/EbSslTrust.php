<?php

namespace ChingShop\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Class EbSslTrust.
 */
class EbSslTrust
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $request->setTrustedProxies([$request->getClientIp()]);

        return $next($request);
    }
}
