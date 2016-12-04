<?php

namespace Testing\Functional\Customer\Sales;

use ChingShop\Modules\Catalogue\Domain\Product\Product;
use ChingShop\Modules\Sales\Domain\Offer\Offer;
use ChingShop\Modules\Sales\Domain\Offer\OfferComponent;
use ChingShop\Modules\Sales\Domain\Offer\OfferSet;
use ChingShop\Modules\Sales\Domain\Offer\PotentialOffer;
use Illuminate\Support\Collection;
use Testing\Functional\FunctionalTest;
use Testing\Functional\Util\CreateCatalogue;

/**
 * Test offer gathering and fulfillment logic.
 */
class OfferSetTest extends FunctionalTest
{
    use CreateCatalogue;

    /** @var int */
    private $mockComponentId = 1;

    /**
     * No offer should be returned if the minimum quantity is not met.
     */
    public function testMinimumQuantityNotMet()
    {
        // Given there is an offer with a minimum quantity of 3;
        /** @var Offer $offer */
        $offer = factory(Offer::class)->create(['quantity' => 3]);
        $product = factory(Product::class)->create();
        $offer->products()->save($product);

        // When we have 2 applicable components;
        $offerSet = new OfferSet($this->offerComponents($product, 2));

        // Then the offer should not be fulfilled.
        $this->assertCount(0, $offerSet->collection());
    }

    /**
     * No offer should be returned if the minimum quantity is not met,
     * regardless of the mix of products.
     */
    public function testMinimumQuantityNotMetWithMixedProducts()
    {
        // Given there is an offer with a minimum quantity of 3;
        /** @var Offer $offer */
        $offer = factory(Offer::class)->create(['quantity' => 5]);
        $products = factory(Product::class)->times(2)->create();
        $offer->products()->saveMany($products);

        // When we have 2 applicable components;
        $offerSet = new OfferSet(
            $this->offerComponents($products->pop(), 2)
                ->merge($this->offerComponents($products->pop(), 2))
        );

        // Then the offer should not be fulfilled.
        $this->assertCount(0, $offerSet->collection());
    }

    /**
     * The potential offer should be returned if the minimum quantity is met.
     */
    public function testMinimumQuantityMet()
    {
        // Given there is an offer with a minimum quantity of 3;
        /** @var Offer $offer */
        $offer = factory(Offer::class)->create(['quantity' => 3]);
        $product = factory(Product::class)->create();
        $offer->products()->save($product);

        // When we have 3 applicable components;
        $offerSet = new OfferSet($this->offerComponents($product, 3));

        // Then the offer should be fulfilled.
        $this->assertCount(1, $offerSet->collection());
    }

    /**
     * The potential offer should be returned if the minimum quantity is met,
     * regardless of the mix of products.
     */
    public function testMinimumQuantityMetWithMixedProducts()
    {
        // Given there is an offer with a minimum quantity of 3;
        /** @var Offer $offer */
        $offer = factory(Offer::class)->create(['quantity' => 3]);
        $products = factory(Product::class)->times(3)->create();
        $offer->products()->saveMany($products);

        // When we have 3 applicable components;
        $offerSet = new OfferSet(
            $this->offerComponents($products->pop(), 1)
                ->merge($this->offerComponents($products->pop(), 1))
                ->merge($this->offerComponents($products->pop(), 1))
        );

        // Then the offer should be fulfilled.
        $this->assertCount(1, $offerSet->collection());
    }

    /**
     * The offer should be fulfilled once if there are extra products but not
     * enough to fulfill the offer twice.
     */
    public function testMinimumQuantityMetWithExtra()
    {
        // Given there is an offer with a minimum quantity of 5;
        /** @var Offer $offer */
        $offer = factory(Offer::class)->create(['quantity' => 5]);
        $product = factory(Product::class)->create();
        $offer->products()->save($product);

        // When we have 7 applicable components;
        $offerSet = new OfferSet($this->offerComponents($product, 7));

        // Then the offer should be fulfilled once.
        $this->assertCount(1, $offerSet->collection());
    }

    /**
     * The offer should be fulfilled once if there are extra products but not
     * enough to fulfill the offer twice, regardless of the mix of products.
     */
    public function testMinimumQuantityMetWithExtraWithMixedProducts()
    {
        // Given there is an offer with a minimum quantity of 3;
        /** @var Offer $offer */
        $offer = factory(Offer::class)->create(['quantity' => 3]);
        $products = factory(Product::class)->times(3)->create();
        $offer->products()->saveMany($products);

        // When we have 5 applicable components;
        $offerSet = new OfferSet(
            $this->offerComponents($products->pop(), 2)
                ->merge($this->offerComponents($products->pop(), 2))
                ->merge($this->offerComponents($products->pop(), 1))
        );

        // Then the offer should be fulfilled.
        $this->assertCount(1, $offerSet->collection());
    }

    /**
     * If there are enough products, then the offer should be fulfilled as many
     * times as possible.
     */
    public function testRepeatOffer()
    {
        // Given there is an offer with a minimum quantity of 3;
        /** @var Offer $offer */
        $offer = factory(Offer::class)->create(['quantity' => 3]);
        $product = factory(Product::class)->create();
        $offer->products()->save($product);

        // When we have 12 applicable components;
        $offerSet = new OfferSet($this->offerComponents($product, 12));

        // Then the offer should be fulfilled four times.
        $this->assertCount(4, $offerSet->collection());
    }

    /**
     * If there are enough products, then the offer should be fulfilled as many
     * times as possible, regardless of the mix of products.
     */
    public function testRepeatOfferWithMixedProducts()
    {
        // Given there is an offer with a minimum quantity of 3;
        /** @var Offer $offer */
        $offer = factory(Offer::class)->create(['quantity' => 3]);
        $products = factory(Product::class)->times(5)->create();
        $offer->products()->saveMany($products);

        // When we have 12 applicable components;
        $offerSet = new OfferSet(
            $this->offerComponents($products->pop(), 3)
                ->merge($this->offerComponents($products->pop(), 3))
                ->merge($this->offerComponents($products->pop(), 3))
                ->merge($this->offerComponents($products->pop(), 3))
        );

        // Then the offer should be fulfilled 4 times.
        $this->assertCount(4, $offerSet->collection());
    }

    /**
     * If there are enough products, then the offer should be fulfilled as many
     * times as possible until the remaining products are not enough to fulfill
     * it more times.
     */
    public function testRepeatOfferWithExtra()
    {
        // Given there is an offer with a minimum quantity of 2;
        /** @var Offer $offer */
        $offer = factory(Offer::class)->create(['quantity' => 2]);
        $product = factory(Product::class)->create();
        $offer->products()->save($product);

        // When we have 5 applicable components;
        $offerSet = new OfferSet($this->offerComponents($product, 5));

        // Then the offer should be fulfilled twice.
        $this->assertCount(2, $offerSet->collection());
    }

    /**
     * If there are enough products, then the offer should be fulfilled as many
     * times as possible until the remaining products are not enough to fulfill
     * it more times, regardless of the mix of products.
     */
    public function testRepeatOfferWithExtraWithMixedProducts()
    {
        // Given there is an offer with a minimum quantity of 2;
        /** @var Offer $offer */
        $offer = factory(Offer::class)->create(['quantity' => 2]);
        $products = factory(Product::class)->times(5)->create();
        $offer->products()->saveMany($products);

        // When we have 7 applicable components;
        $offerSet = new OfferSet(
            $this->offerComponents($products->pop(), 2)
                ->merge($this->offerComponents($products->pop(), 3))
                ->merge($this->offerComponents($products->pop(), 1))
                ->merge($this->offerComponents($products->pop(), 1))
        );

        // Then the offer should be fulfilled 3 times.
        $this->assertCount(3, $offerSet->collection());
    }

    /**
     * Different offers should be able to appear together in the same set.
     */
    public function testDifferentOffersTogether()
    {
        // Given there is one offer with a minimum quantity of 3;
        /** @var Offer $offerA */
        $offerA = factory(Offer::class)->create(['quantity' => 3]);
        $productA = factory(Product::class)->create();
        $offerA->products()->save($productA);

        // And another offer with a minimum quantity of 2;
        /** @var Offer $offerB */
        $offerB = factory(Offer::class)->create(['quantity' => 2]);
        $productB = factory(Product::class)->create();
        $offerB->products()->save($productB);

        // When there are enough of the applicable components;
        $offerSet = new OfferSet(
            $this->offerComponents($productA, 4)
                ->merge($this->offerComponents($productB, 3))
        );

        // Then both offers should be fulfilled.
        $this->assertCount(2, $offerSet->collection());
        $offerIds = $offerSet->collection()->map(
            function (PotentialOffer $potentialOffer) {
                return $potentialOffer->offer()->id;
            }
        )->values()->all();
        $this->assertContains($offerA->id, $offerIds);
        $this->assertContains($offerB->id, $offerIds);
    }

    /**
     * Should be able to get an absolute price discount.
     */
    public function testAbsolutePriceDiscount()
    {
        // Given there is an offer with an absolute price of £10;
        /** @var Offer $offer */
        $offer = factory(Offer::class)->states('price')->create(
            ['quantity' => 3, 'effect' => Offer::ABSOLUTE, 'price' => 1000]
        );

        // With a product with a normal price of £3.50;
        $product = $this->createProductWithPrice(3, 50);
        $offer->products()->save($product);

        // When we have enough applicable components;
        $offerSet = new OfferSet($this->offerComponents($product, 3));

        // Then the discount should be £0.50 (£10.50 - £0.50 = £10.00).
        $this->assertCount(1, $offerSet->collection());
        /** @var PotentialOffer $potentialOffer */
        $potentialOffer = $offerSet->collection()->first();
        $this->assertEquals(-50, $potentialOffer->linePrice()->amount());
        $this->assertEquals(
            '-£0.50',
            $potentialOffer->linePrice()->formatted()
        );
    }

    /**
     * Should be able to get a relative price discount.
     */
    public function testRelativePriceDiscount()
    {
        // Given there is an offer with a relative price of £5;
        /** @var Offer $offer */
        $offer = factory(Offer::class)->states('price')->create(
            ['quantity' => 2, 'effect' => Offer::RELATIVE, 'price' => 500]
        );

        // With a product;
        $product = $this->createProductWithPrice(10);
        $offer->products()->save($product);

        // When we have enough applicable components;
        $offerSet = new OfferSet($this->offerComponents($product, 3));

        // Then the discount should be £5.00.
        $this->assertCount(1, $offerSet->collection());
        /** @var PotentialOffer $potentialOffer */
        $potentialOffer = $offerSet->collection()->first();
        $this->assertEquals(-500, $potentialOffer->linePrice()->amount());
        $this->assertEquals(
            '-£5.00',
            $potentialOffer->linePrice()->formatted()
        );
    }

    /**
     * Should be able to get an absolute percentage discount.
     */
    public function testAbsolutePercentageDiscount()
    {
        // Given there is an offer with an absolute percentage of 75%;
        /** @var Offer $offer */
        $offer = factory(Offer::class)->states('percentage')->create(
            ['quantity' => 3, 'effect' => Offer::ABSOLUTE, 'percentage' => 75]
        );

        // With a product with a normal price of £2.95;
        $product = $this->createProductWithPrice(2, 95);
        $offer->products()->save($product);

        // When we have enough applicable components;
        $offerSet = new OfferSet($this->offerComponents($product, 3));

        // Then the discount should be £2.21 (£8.85 * 25%);
        $this->assertCount(1, $offerSet->collection());
        /** @var PotentialOffer $potentialOffer */
        $potentialOffer = $offerSet->collection()->first();
        $this->assertEquals(-221, $potentialOffer->linePrice()->amount());
        $this->assertEquals(
            '-£2.21',
            $potentialOffer->linePrice()->formatted()
        );
    }

    /**
     * Should be able to get a relative percentage discount.
     */
    public function testRelativePercentageDiscount()
    {
        // Given there is an offer with a relative percentage of 30%;
        /** @var Offer $offer */
        $offer = factory(Offer::class)->states('percentage')->create(
            ['quantity' => 5, 'effect' => Offer::RELATIVE, 'percentage' => 30]
        );

        // With a product with a normal price of £3.25;
        $product = $this->createProductWithPrice(3, 25);
        $offer->products()->save($product);

        // When we have enough applicable components;
        $offerSet = new OfferSet($this->offerComponents($product, 5));

        // Then the discount should be £4.88 (£16.25 * 30%);
        $this->assertCount(1, $offerSet->collection());
        /** @var PotentialOffer $potentialOffer */
        $potentialOffer = $offerSet->collection()->first();
        $this->assertEquals(-488, $potentialOffer->linePrice()->amount());
        $this->assertEquals(
            '-£4.88',
            $potentialOffer->linePrice()->formatted()
        );
    }

    /**
     * Should be able to use an absolute price discount repeatedly if there are
     * enough components.
     */
    public function testRepeatedAbsolutePriceDiscounts()
    {
        // Given there is an offer with an absolute price of £8;
        /** @var Offer $offer */
        $offer = factory(Offer::class)->states('price')->create(
            ['quantity' => 3, 'effect' => Offer::ABSOLUTE, 'price' => 800]
        );

        // With a product with a normal price of £3.85;
        $product = $this->createProductWithPrice(3, 85);
        $offer->products()->save($product);

        // When we have enough applicable components to get the offer 3 times;
        $offerSet = new OfferSet($this->offerComponents($product, 11));

        // Then we should have 3 discounts of £3.55 each (£11.85 - £3.55 = £8);
        $this->assertCount(3, $offerSet->collection());
        $offerSet->collection()->each(
            function (PotentialOffer $potentialOffer) {
                $this->assertEquals(
                    -355,
                    $potentialOffer->linePrice()->amount()
                );
                $this->assertEquals(
                    '-£3.55',
                    $potentialOffer->linePrice()->formatted()
                );
            }
        );
    }

    /**
     * Should be able to get multiple absolute price discounts on one set of
     * components, given all the offer requirements are fulfilled.
     */
    public function testMultipleDifferentAbsolutePriceDiscount()
    {
        // Given there is an offer with an absolute price of £15;
        /** @var Offer $offerA */
        $offerA = factory(Offer::class)->states('price')->create(
            ['quantity' => 3, 'effect' => Offer::ABSOLUTE, 'price' => 1500]
        );

        // For a product with a normal price of £6;
        $productA = $this->createProductWithPrice(6, 00);
        $offerA->products()->save($productA);

        // And another offer with an absolute price of £10;
        /** @var Offer $offerB */
        $offerB = factory(Offer::class)->states('price')->create(
            ['quantity' => 2, 'effect' => Offer::ABSOLUTE, 'price' => 1000]
        );

        // For a product with a normal price of £8;
        $productB = $this->createProductWithPrice(8, 00);
        $offerB->products()->save($productB);

        // When we have enough components to get both offers;
        $offerSet = new OfferSet(
            $this->offerComponents($productA, 5)
                ->merge($this->offerComponents($productB, 3))
        );

        // Then we should get 2 discounts;
        $potentialOffers = $offerSet->collection();
        $this->assertCount(2, $potentialOffers);

        // One discount of £3 for the first offer;
        /** @var PotentialOffer $potentialOfferA */
        $potentialOfferA = $potentialOffers->pop();
        $this->assertEquals($potentialOfferA->offer()->id, $offerA->id);
        $this->assertEquals(-300, $potentialOfferA->linePrice()->amount());
        $this->assertEquals(
            '-£3.00',
            $potentialOfferA->linePrice()->formatted()
        );

        // And another discount of £6 for the second offer.
        /** @var PotentialOffer $potentialOfferB */
        $potentialOfferB = $potentialOffers->pop();
        $this->assertEquals($potentialOfferB->offer()->id, $offerB->id);
        $this->assertEquals(-600, $potentialOfferB->linePrice()->amount());
        $this->assertEquals(
            '-£6.00',
            $potentialOfferB->linePrice()->formatted()
        );
    }

    /**
     * Should be able to get repeated absolute price discounts for an offer with
     * mixed products.
     */
    public function testMixedProductsRepeatedAbsolutePriceDiscounts()
    {
        // Given there is an offer with an absolute price of £12;
        /** @var Offer $offerA */
        $offer = factory(Offer::class)->states('price')->create(
            ['quantity' => 3, 'effect' => Offer::ABSOLUTE, 'price' => 1200]
        );

        // With a product costing £4.50;
        $productA = $this->createProductWithPrice(4, 50);
        $offer->products()->save($productA);

        // And a product costing £5.00;
        $productB = $this->createProductWithPrice(5, 00);
        $offer->products()->save($productB);

        // When we have enough components to get the offer twice with mixed
        // products;
        $offerSet = new OfferSet(
            $this->offerComponents($productA, 2)
                ->merge($this->offerComponents($productB, 1))
                ->merge($this->offerComponents($productA, 1))
                ->merge($this->offerComponents($productB, 2))
        );

        // Then we should get 2 discounts;
        $potentialOffers = $offerSet->collection();
        $this->assertCount(2, $potentialOffers);

        // One for £1.50: (£4.50 + £4.50 + £4.50) - £1.50 = £12;
        /** @var PotentialOffer $potentialOfferA */
        $potentialOfferA = $potentialOffers->pop();
        $this->assertEquals(-150, $potentialOfferA->linePrice()->amount());
        $this->assertEquals(
            '-£1.50',
            $potentialOfferA->linePrice()->formatted()
        );

        // And one for £3.00: (£5.00 + £5.00 + £5.00) - £3.00 = £12;
        /** @var PotentialOffer $potentialOfferB */
        $potentialOfferB = $potentialOffers->pop();
        $this->assertEquals(-300, $potentialOfferB->linePrice()->amount());
        $this->assertEquals(
            '-£3.00',
            $potentialOfferB->linePrice()->formatted()
        );
    }

    /**
     * @param Product $product
     * @param int     $count
     *
     * @return Collection
     */
    private function offerComponents(Product $product, int $count): Collection
    {
        $componentId = &$this->mockComponentId;

        return (new Collection(array_fill(0, $count, '')))->map(
            function () use ($product, &$componentId) {
                return new class($product, ++$componentId) implements OfferComponent {
                    /** @var Product */
                    private $product;

                    /** @var int */
                    private $id;

                    /**
                     * @param Product $product
                     * @param int     $id
                     */
                    public function __construct(Product $product, int $id)
                    {
                        $this->product = $product;
                        $this->id = $id;
                    }

                    /**
                     * @return int
                     */
                    public function id(): int
                    {
                        return $this->id;
                    }

                    /**
                     * @return Product
                     */
                    public function product(): Product
                    {
                        return $this->product;
                    }
                };
            }
        );
    }
}
