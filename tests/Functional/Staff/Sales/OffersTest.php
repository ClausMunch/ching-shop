<?php

namespace Testing\Functional\Staff\Sales;

use ChingShop\Modules\Sales\Domain\Offer\Offer;
use Testing\Functional\FunctionalTest;
use Testing\Functional\Staff\StaffUser;
use Testing\Functional\Util\CreateCatalogue;

/**
 * Test staff offer management functionality.
 */
class OffersTest extends FunctionalTest
{
    use StaffUser, CreateCatalogue;

    /**
     * Should be able to view the offers index.
     *
     * @slowThreshold 1000
     */
    public function testCanViewOffersIndex()
    {
        $this->actingAs($this->staffUser())
            ->visit(route('staff.dashboard'))
            ->click('Offers')
            ->seePageIs(route('offers.index'))
            ->see('Offers');
    }

    /**
     * Non-staff users should not be able to view the offers index.
     */
    public function testNonStaffUserCantViewOffersIndex()
    {
        $this->visit(route('offers.index'))->seePageIs(route('login'));
    }

    /**
     * Should be able to create a fixed price offer.
     */
    public function testCanCreateAbsolutePriceOffer()
    {
        $this->actingAs($this->staffUser())
            ->visit(route('offers.index'))
            ->click('Create new offer')
            ->type(1000, 'price')
            ->type(3, 'quantity')
            ->select('absolute', 'effect')
            ->press('Save')
            ->see('Created new offer')
            ->see('3 for £10');
    }

    /**
     * Should be able to create a discount offer.
     */
    public function testCanCreateRelativePriceOffer()
    {
        $this->actingAs($this->staffUser())
            ->visit(route('offers.index'))
            ->click('Create new offer')
            ->type(200, 'price')
            ->type(1, 'quantity')
            ->select('relative', 'effect')
            ->press('Save')
            ->see('Created new offer')
            ->see('£2 off');
    }

    /**
     * Should be able to create a fixed percentage offer.
     */
    public function testCanCreateAbsolutePercentageOffer()
    {
        $this->actingAs($this->staffUser())
            ->visit(route('offers.index'))
            ->click('Create new offer')
            ->type(75, 'percentage')
            ->type(1, 'quantity')
            ->select('absolute', 'effect')
            ->press('Save')
            ->see('Created new offer')
            ->see('25% off');
    }

    /**
     * Should be able to create a percentage discount offer.
     */
    public function testCanCreateRelativePercentageOffer()
    {
        $this->actingAs($this->staffUser())
            ->visit(route('offers.index'))
            ->click('Create new offer')
            ->type(15, 'percentage')
            ->type(2, 'quantity')
            ->select('relative', 'effect')
            ->press('Save')
            ->see('Created new offer')
            ->see('15% off when you buy 2');
    }

    /**
     * Should be able to choose the colour for an offer.
     */
    public function testCanSetOfferColour()
    {
        $colour = '#112233';

        $this->actingAs($this->staffUser())
            ->visit(route('offers.index'))
            ->click('Create new offer')
            ->type(15, 'percentage')
            ->type($colour, 'colour')
            ->press('Save')
            ->see('Created new offer')
            ->see($colour);
    }

    /**
     * Should be able to select the products for an offer.
     */
    public function testCanSetProductsForOffer()
    {
        /** @var Offer $offer */
        $offer = factory(Offer::class)->create();
        $product = $this->createProduct();

        $this->actingAs($this->staffUser())
            ->visit(route('offers.index'))
            ->see($offer->name)
            ->select($product->id, 'product-ids[]')
            ->press("Save {$offer->name} products")
            ->see('Set products for offer');

        $offer->fresh('products');
        $this->assertContains($product->id, $offer->products->pluck('id'));
    }

    /**
     * Should be able to select the offers for a product.
     */
    public function testCanSetOffersForProduct()
    {
        /** @var Offer $offer */
        $offer = factory(Offer::class)->create();
        $product = $this->createProduct();

        $this->actingAs($this->staffUser())
            ->visit(route('products.show', ['sku' => $product->sku]))
            ->select($offer->id, 'offer-ids[]')
            ->press('Save offers')
            ->see('Set offers');

        $product->fresh('offers');
        $this->assertContains($offer->id, $product->offers->pluck('id'));
    }

    /**
     * Should be able to edit an offer.
     */
    public function testCanEditOffer()
    {
        $this->actingAs($this->staffUser())
            ->visit(route('offers.index'))
            ->click('Create new offer')
            ->type(15, 'percentage')
            ->type(1, 'quantity')
            ->select('relative', 'effect')
            ->press('Save')
            ->see('Created new offer')
            ->see('15% off');

        $this->click('Edit 15% off')
            ->see('Edit `15% off` offer')
            ->type(1000, 'price')
            ->type('', 'percentage')
            ->type(3, 'quantity')
            ->select('absolute', 'effect')
            ->press('Save')
            ->see('Updated')
            ->see('3 for £10');
    }

    /**
     * Should be able to delete an offer.
     */
    public function testCanDeleteOffer()
    {
        $offer = factory(Offer::class)->create();

        $this->actingAs($this->staffUser())
            ->visit(route('offers.index'))
            ->see($offer->name)
            ->press("Delete {$offer->name}")
            ->see('Deleted');

        $this->markTestIncomplete();
    }
}
