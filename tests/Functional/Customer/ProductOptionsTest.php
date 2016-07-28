<?php

namespace Testing\Customer;

use ChingShop\Modules\Catalogue\Model\Product\ProductOption;
use Testing\Functional\FunctionalTest;
use Testing\Functional\Util\CreateCatalogue;

class ProductOptionsTest extends FunctionalTest
{
    use CreateCatalogue;

    /**
     * Should be able to see select options on the page for each product option.
     *
     * @slowThreshold 600
     */
    public function testCanSeeOptionsOnProductPage()
    {
        // Given we have a product with several options;
        $product = $this->createProduct();
        /** @var ProductOption[] $productOptions */
        $productOptions = [];
        for ($i = 0; $i < 3; $i++) {
            $productOptions[] = $this->createProductOptionFor($product);
        }

        // When we visit the product page for that product;
        $this->visit(route('product::view', [$product->id, $product->slug]))
            ->see($product->name);

        // Then we should be able to see a select option for each product
        // option.
        foreach ($productOptions as $productOption) {
            $this->see($productOption->id);
            $this->see($productOption->label);
        }
    }

    /**
     * If there is only one option for a product, it should be pre-selected in
     * a hidden field on page load.
     */
    public function testSingleOptionIsSelected()
    {
        // Given we have a product with one product option;
        $product = $this->createProduct();
        $productOption = $this->createProductOptionFor($product);

        // When we visit the product page for that product;
        $this->visit(route('product::view', [$product->id, $product->slug]))
            ->see($product->name);

        // Then that option should be pre-selected.
        $this->assertEquals(
            $productOption->id,
            $this->crawler()
                ->filter('[name=product-option]')
                ->first()
                ->attr('value')
        );
    }
}
