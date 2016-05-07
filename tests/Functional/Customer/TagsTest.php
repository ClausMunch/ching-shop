<?php

namespace Testing\Functional\Customer;

use Testing\Functional\FunctionalTest;
use Testing\Functional\Util\CreateCatalogue;

class TagsTest extends FunctionalTest
{
    use CreateCatalogue;

    /**
     * The customer product page should show the product's tags.
     */
    public function testShouldBeAbleToSeeTagsOnProductPage()
    {
        $product = $this->createProduct();
        $tag = $this->createTag();
        $tag->products()->attach($product->id);

        $this->visit(route('product::view', [$product->id, $product->slug]))
            ->see($product->name)
            ->see($tag->name)
            ->click($tag->name)
            ->seePageIs(route('tag::view', [$tag->id, $tag->name]));
    }

    /**
     * A tag page should show products with that tag.
     */
    public function testShouldBeAbleToSeeTaggedProductOnTagPage()
    {
        $product = $this->createProduct();
        $tag = $this->createTag();
        $tag->products()->attach($product->id);

        $this->visit(route('tag::view', [$tag->id, $tag->name]))
            ->see($tag->name)
            ->see($product->name)
            ->click($product->name)
            ->seePageIs(route('product::view', [$product->id, $product->slug]));
    }
}
