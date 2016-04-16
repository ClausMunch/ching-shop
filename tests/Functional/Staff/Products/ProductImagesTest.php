<?php

namespace Testing\Functional\Staff\Products;

use Testing\Functional\Staff\StaffUser;

class ProductImagesTest extends ProductTest
{
    use StaffUser;

    /**
     * A product with an image should be shown on a category page.
     */
    public function testProductWithImagesShownOnCategoryPage()
    {
        $product = $this->makeProduct();
        $image = $this->attachImageToProduct($product);

        $this->visit(route('customer.cards'))
            ->seePageIs(route('customer.cards'))
            ->see($product->slug)
            ->see($product->description)
            ->see($image->url)
            ->see($image->alt_text);
    }

    /**
     * A product with no images should not be shown on a category page.
     */
    public function testProductWithoutImagesNotShownOnCategoryPage()
    {
        $product = $this->makeProduct();
        $product->images()->detach();

        $this->visit(route('customer.cards'))
            ->seePageIs(route('customer.cards'))
            ->dontSee($product->sku)
            ->dontSee($product->slug)
            ->dontSee($product->description);
    }
}
