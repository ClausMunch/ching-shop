<?php

namespace ChingShop\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

/**
 * Class ForceSecure.
 */
class ForceSecure
{
    /** @var Redirector */
    private $redirector;

    /**
     * @param Redirector $redirector
     */
    public function __construct(Redirector $redirector)
    {
        $this->redirector = $redirector;
    }

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
        if (!$request->isSecure()) {
            return $this->redirector->to(
                $request->getRequestUri(),
                302,
                $request->headers->all(),
                true
            );
        }

        return $next($request);
    }
}
