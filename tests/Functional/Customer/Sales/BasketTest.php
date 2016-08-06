<?php

namespace Testing\Functional\Sales;

use ChingShop\Modules\Catalogue\Model\Product\ProductOption;
use Testing\Functional\Customer\CustomerUsers;
use Testing\Functional\FunctionalTest;
use Testing\Functional\Util\CreateCatalogue;

/**
 * Class BasketTest.
 *
 * Test basic shopping basket functionality.
 */
class BasketTest extends FunctionalTest
{
    use CreateCatalogue, CustomerUsers;

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
        $this->actingAs($this->customerUser())
            ->visit(route('product::view', [$product->id, $product->slug]))
            ->see($product->name)
            ->press('Add to basket')
            ->see('added');

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
        $this->actingAs($this->customerUser())
            ->visit(route('product::view', [$product->id, $product->slug]))
            ->see($product->name)
            ->press('Add to basket');

        // When we remove it from the basket;
        $this->press("Remove {$product->name}")
            ->see('removed');

        // Then it should not be in the basket.
        $this->actingAs($this->customerUser())
            ->visit(route('sales.customer.basket'))
            ->dontSee($option->label);
    }
}
