<?php

namespace Testing\Functional\Customer\Sales\Offers;

use ChingShop\Modules\Catalogue\Domain\Product\Product;
use ChingShop\Modules\Sales\Domain\Offer\Offer;
use Illuminate\Database\Eloquent\Collection;
use Testing\Functional\FunctionalTest;
use Testing\Functional\Util\CreateCatalogue;
use Testing\TestUtil;

/**
 * Test customer sales offers functionality.
 */
class OffersTest extends FunctionalTest
{
    use CreateCatalogue, TestUtil;

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

    /**
     * Should be able to view products with special offers.
     */
    public function testOfferProductsPage()
    {
        // Given there are offers with products;
        /** @var Collection|Offer[] $offers */
        $offers = factory(Offer::class)->times(2)->create();
        $offers->each(
            function (Offer $offer) {
                $offer->products()->saveMany(
                    factory(Product::class)->times(2)->create()
                );
            }
        );

        // When we go to the offer products page;
        $this->visit(route('offers.products'));

        // We should see the offers;
        $offers->each(
            function (Offer $offer) {
                $this->see($offer->name);
            }
        );

        // And all their products;
        $offers->each(
            function (Offer $offer) {
                $this->assertGreaterThanOrEqual(1, $offer->products->count());
                $offer->products->each(
                    function (Product $product) {
                        $this->see($product->name);
                    }
                );
            }
        );
    }

    /**
     * Should be able to go to a special offer's page from a product with that
     * offer.
     */
    public function testCanGoToOfferPageFromProduct()
    {
        // Given there is an offer with a product;
        /** @var Offer $offer */
        $offer = factory(Offer::class)->create();
        /** @var Product $product */
        $product = $this->createProduct();
        $offer->products()->save($product);

        // When we go to that product's page;
        $this->visit(route('product::view', [$product->id, $product->slug]))
            ->see($offer->name);

        // Then we should be able to go to the offer's page from there.
        $this->click($offer->name)
            ->seePageIs(route('offers.view', [$offer->id, $offer->slug()]))
            ->see($offer->name)
            ->see($product->name);
    }

    /**
     * Should be able to go to special offer's page from a product list page.
     */
    public function testCanGoToOfferPageFromListPage()
    {
        // Given there is an offer with a product with a tag;
        /** @var Offer $offer */
        $offer = factory(Offer::class)->create();
        /** @var Product $product */
        $product = $this->createProduct();
        $offer->products()->save($product);
        $tag = $this->createTag();
        $tag->products()->save($product);

        // When we go to that tag's page;
        $this->visit(route('tag::view', [$tag->id, $tag->name]));

        // Then we should be able to go to the offer's page from there.
        $this->click($offer->name)
            ->seePageIs(route('offers.view', [$offer->id, $offer->slug()]))
            ->see($offer->name)
            ->see($product->name);
    }
}
