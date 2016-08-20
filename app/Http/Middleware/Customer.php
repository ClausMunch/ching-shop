<?php

namespace ChingShop\Http\Middleware;

use ChingShop\Http\View\Customer\LocationComposer;
use ChingShop\Modules\Sales\Domain\Clerk;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class Customer.
 */
class Customer
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
     * @param Request  $request
     * @param \Closure $next
     *
     * @return Response
     */
    public function handle(Request $request, Closure $next)
    {
        view()->creator(
            '*',
            function ($view) {
                $view->with('basket', $this->clerk->basket());
            }
        );

        view()->composer('*', LocationComposer::class);

        return $next($request);
    }
}
