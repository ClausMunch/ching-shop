<?php

namespace ChingShop\Http\Middleware;

use ChingShop\Http\View\Customer\LocationComposer;
use ChingShop\Modules\Catalogue\Domain\Tag\Tag;
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

    /** @var Tag */
    private $tagResource;

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
            '*',
            function ($view) {
                $view->with('basket', $this->clerk->basket());
                $view->with(
                    'suggestions',
                    $this->tagResource->limit(100)->get(['name'])
                );
            }
        );

        view()->composer('*', LocationComposer::class);

        return $next($request);
    }
}
