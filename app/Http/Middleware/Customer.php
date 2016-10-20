<?php

namespace ChingShop\Http\Middleware;

use ChingShop\Http\View\Customer\LocationComposer;
use ChingShop\Modules\Catalogue\Domain\Tag\Tag;
use ChingShop\Modules\Sales\Domain\Clerk;
use Closure;
use Illuminate\Contracts\View\View;
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
     * @param Tag   $tagResource
     */
    public function __construct(Clerk $clerk, Tag $tagResource)
    {
        $this->clerk = $clerk;
        $this->tagResource = $tagResource;
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
            [
                'customer.partials.mini-basket',
                'customer.basket.view',
                'customer.checkout.section',
                'customer.checkout.address',
            ],
            function (View $view) {
                $view->with('basket', $this->clerk->basket());
            }
        );

        view()->composer('*', LocationComposer::class);

        return $next($request);
    }
}
