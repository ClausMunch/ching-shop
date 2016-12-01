<?php

namespace Testing\Functional\Customer\Sales;

use ChingShop\Modules\Sales\Domain\Offer\Offer;
use Testing\Functional\FunctionalTest;
use Testing\Functional\Util\CreateCatalogue;

/**
 * Test customer sales offers functionality.
 */
class OffersTest extends FunctionalTest
{
    use CreateCatalogue;

    /**
     * Should be able to see an offer for a product on the product's page.
     */
    public function testCanSeeProductOffer()
    {
        // Given there is a product with an offer;
        $product = $this->createProduct();
        $this->createProductOptionFor($product);
        $offer = factory(Offer::class)->create();
        $product->offers()->save($offer);

        // When we visit the product's page;
        $this->visit(route('product::view', [$product->id, $product->slug]))
            ->see($product->name);

        // Then we should see the offer.
        $this->see($offer->name);
    }
}
