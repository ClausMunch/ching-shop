<?php

namespace ChingShop\Modules\Catalogue\Http\Controllers;

use ChingShop\Http\Controllers\Controller;
use ChingShop\Http\WebUi;
use ChingShop\Modules\Catalogue\Domain\Product\Product;
use ChingShop\Modules\Catalogue\Domain\Tag\Tag;

/**
 * Custom landing page display actions.
 */
class LandingController extends Controller
{
    /** @var Tag */
    private $tagResource;

    /** @var WebUi */
    private $webUi;

    /**
     * LandingController constructor.
     *
     * @param Tag   $tagResource
     * @param WebUi $webUi
     */
    public function __construct(Tag $tagResource, WebUi $webUi)
    {
        $this->tagResource = $tagResource;
        $this->webUi = $webUi;
    }

    /**
     * Display the "Christmas Cards" landing page.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function christmasCardsAction()
    {
        return $this->webUi->view(
            'customer.landing.christmas-cards',
            [
                'products' => $this->tagResource
                    ->where(
                        'name',
                        '=',
                        'Christmas'
                    )
                    ->with(
                        [
                            'products' => function ($query) {
                                /** @var Product $query */
                                $query->inStock();
                                $query->with(
                                    [
                                        'images',
                                        'options',
                                        'options.images',
                                        'options.stockItems',
                                    ]
                                );
                            },
                        ]
                    )
                    ->first()
                    ->products,
            ]
        );
    }
}
