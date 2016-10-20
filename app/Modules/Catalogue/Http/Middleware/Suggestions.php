<?php

namespace ChingShop\Modules\Catalogue\Http\Middleware;

use ChingShop\Modules\Catalogue\Domain\CatalogueView;
use ChingShop\Modules\Catalogue\Domain\Tag\Tag;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Add search suggestions to views.
 */
class Suggestions
{
    /** @var \ChingShop\Modules\Catalogue\CatalogueView */
    private $catalogueViewCache;

    /**
     * Suggestions constructor.
     *
     * @param Tag                                               $tagResource
     * @param \ChingShop\Modules\Catalogue\Domain\CatalogueView $catalogueViewCache
     */
    public function __construct(
        Tag $tagResource,
        CatalogueView $catalogueViewCache
    ) {
        $this->tagResource = $tagResource;
        $this->catalogueViewCache = $catalogueViewCache;
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
            ['customer.partials.search'],
            function (View $view) {
                $view->with(
                    'suggestionsCache',
                    $this->catalogueViewCache->suggestions()
                );
            }
        );

        return $next($request);
    }
}
