<?php

namespace ChingShop\Modules\Sales\Http\Middleware;

use ChingShop\Modules\Sales\Domain\Clerk;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class CheckoutMiddleware.
 */
class CheckoutMiddleware
{
    /** @var Clerk */
    private $clerk;

    /**
     * @param Clerk $clerk
     */
    public function __construct(Clerk $clerk)
    {
        $this->clerk = $clerk;
    }

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     *
     * @return Response
     */
    public function handle(Request $request, Closure $next)
    {
        $this->clerk->basket()->load('address');

        view()->share('address', $this->clerk->basket()->address);

        return $next($request);
    }
}
