<?php

namespace Testing\Functional\Customer\Sales;

use ChingShop\Modules\Catalogue\Domain\Product\ProductOption;
use Testing\Functional\FunctionalTest;
use Testing\Functional\Util\SalesInteractions;
use Testing\TestUtil;

/**
 * Class BasketTest.
 *
 * Test shopping basket functionality.
 *
 * @group sales
 */
class BasketTest extends FunctionalTest
{
    use SalesInteractions, TestUtil;

    /**
     * Should be able to see the mini-basket.
     *
     * @slowThreshold 800
     */
    public function testCanSeeMiniBasket()
    {
        $this->visit('/')->see('mini-basket');
    }

    /**
     * Should be able to visit the basket page.
     */
    public function testCanVisitBasketPage()
    {
        $this->visit(route('sales.customer.basket'))
            ->assertResponseOk();
    }

    /**
     * Should be able to add a product with only one product option to the
     * basket.
     */
    public function testCanAddSingleOptionProductToBasket()
    {
        // Given there is a product with a single option;
        $product = $this->createProduct();
        $option = $this->createProductOptionFor($product);

        // When we add it to the basket;
        $this->addProductToBasket($product, $this);

        // Then we should see it in the basket.
        $this->actingAs($this->customerUser())
            ->visit(route('sales.customer.basket'))
            ->see($product->name)
            ->see($option->label);
    }

    /**
     * Should be able to add a product with multiple options to choose from to
     * the basket.
     */
    public function testCanAddProductOptionChoiceToBasket()
    {
        // Given there is a product with a multiple options;
        $product = $this->createProduct();
        /* @var ProductOption[] $options */
        $options[] = $this->createProductOptionFor($product);
        $options[] = $this->createProductOptionFor($product);

        // When we add one of the options to the basket;
        $this->actingAs($this->customerUser())
            ->visit(route('product::view', [$product->id, $product->slug]))
            ->see($product->name);
        foreach ($options as $option) {
            $this->see($option->label);
        }
        $this->select($options[0]->id, 'product-option')
            ->press('Add to basket')
            ->see('added');

        // Then we should see that product option in the basket.
        $this->actingAs($this->customerUser())
            ->visit(route('sales.customer.basket'))
            ->see($product->name)
            ->see($options[0]->label);

        // And should not see the other option.
        $this->dontSee($options[1]->label);
    }

    /**
     * Should be able to remove an item from the basket.
     */
    public function testCanRemoveItemFromBasket()
    {
        // Given there is a product with an option;
        $product = $this->createProduct();
        $option = $this->createProductOptionFor($product);

        // And we add it to the basket;
        $this->addProductToBasket($product, $this);

        // When we remove it from the basket;
        $this->press("Remove {$product->name}")->see('removed');

        // Then it should not be in the basket.
        $this->actingAs($this->customerUser())
            ->visit(route('sales.customer.basket'))
            ->dontSee($option->label);
    }

    /**
     * Should be able to see a count of items in the basket.
     */
    public function testCanSeeMiniBasketCount()
    {
        // Given there is a product with an option;
        $product = $this->createProduct();
        $this->createProductOptionFor($product);

        // And we have an empty basket;
        $this->actingAs($this->customerUser())
            ->visit(route('product::view', [$product->id, $product->slug]))
            ->assertEquals(0, $this->getElementText('#mini-basket-count'));

        // When we add the product to the basket three times;
        $this->repeat(
            3,
            function () use ($product) {
                $this->addProductToBasket($product, $this);
            }
        );

        // And remove it once;
        $this->press("Remove {$product->name}")->see('removed');

        // Then we should see the count in the mini basket.
        $this->actingAs($this->customerUser())
            ->visit(route('product::view', [$product->id, $product->slug]))
            ->assertEquals(2, $this->getElementText('#mini-basket-count'));
    }

    /**
     * Should be able to see the total price of items in the basket.
     */
    public function testCanSeeBasketTotalPrice()
    {
        // Given there is a product with a price;
        $product = $this->createProduct();
        $this->createProductOptionFor($product);
        $price = $this->createPriceForProduct($product);

        // When we add it to the basket three times;
        $this->repeat(
            3,
            function () use ($product) {
                $this->addProductToBasket($product, $this);
            }
        );

        // Then the total should be shown on the basket page.
        $this->actingAs($this->customerUser())
            ->visit(route('sales.customer.basket'))
            ->see($price->asFloat() * 3);
    }

    /**
     * Trailing zeros should be displayed in the basket total.
     */
    public function testBasketPriceFormatTwoTrailingZeros()
    {
        // Given there is a product with a price with 0 subunits;
        $product = $this->createProduct();
        $this->createProductOptionFor($product);
        $price = $this->createPriceForProduct($product);
        $price->units = 5;
        $price->subunits = 0;
        $price->save();

        // When we add it to the basket;
        $this->addProductToBasket($product, $this);

        // Then the total shown should have trailing zeros.
        $this->actingAs($this->customerUser())
            ->visit(route('sales.customer.basket'))
            ->assertSame(
                '5.00',
                trim($this->getElementText('.basket-total-amount'))
            );
    }

    /**
     * A trailing zero should be displayed in the basket total.
     */
    public function testBasketPriceFormatTrailingZero()
    {
        // Given there is a product with a price with x10 subunits;
        $product = $this->createProduct();
        $this->createProductOptionFor($product);
        $price = $this->createPriceForProduct($product);
        $price->units = 4;
        $price->subunits = 20;
        $price->save();

        // When we add it to the basket;
        $this->addProductToBasket($product, $this);

        // Then the total shown should have trailing zeros.
        $this->actingAs($this->customerUser())
            ->visit(route('sales.customer.basket'))
            ->assertSame(
                '4.20',
                trim($this->getElementText('.basket-total-amount'))
            );
    }

    /**
     * Should be able to proceed to checkout from the basket.
     */
    public function testCanGoToCheckoutFromBasket()
    {
        // Given we have an item in the basket;
        $product = $this->createProduct();
        $this->createProductOptionFor($product);
        $this->addProductToBasket($product, $this);

        // When we go to the basket;
        $this->actingAs($this->customerUser())
            ->visit(route('sales.customer.basket'));

        // We should be able click a link to continue to checkout.
        $this->click('Go to checkout')
            ->seePageIs(route('sales.customer.checkout.address'));
    }
}
