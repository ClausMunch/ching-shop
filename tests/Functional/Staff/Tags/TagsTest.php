<?php

namespace Testing\Functional\Staff\Tags;

use ChingShop\Modules\Catalogue\Domain\Product\Product;
use Testing\Functional\FunctionalTest;
use Testing\Functional\Staff\StaffUser;
use Testing\Functional\Util\CreateCatalogue;

class TagsTest extends FunctionalTest
{
    use StaffUser, CreateCatalogue;

    /**
     * Should be able to visit the tags index.
     */
    public function testCanVisitTagsIndex()
    {
        $this->actingAs($this->staffUser())
            ->visit(route('staff.dashboard'))
            ->seePageIs(route('staff.dashboard'))
            ->see(route('tags.index'))
            ->click('Tags')
            ->seePageIs(route('tags.index'));

        $this->assertResponseOk();
    }

    /**
     * Should be able to see tags in the tag index.
     */
    public function testCanSeeTagsInTagIndex()
    {
        $firstTag = $this->createTag();
        $secondTag = $this->createTag();

        $this->goToTagIndex()
            ->see($firstTag->name)
            ->see($secondTag->name);
    }

    /**
     * Should be able to create a tag.
     */
    public function testCanCreateTag()
    {
        $tagName = str_random();

        $this->goToTagIndex()
            ->type($tagName, 'name')
            ->press('add-new-tag')
            ->seePageIs(route('tags.index'))
            ->see('Created')
            ->see($tagName);
    }

    /**
     * Should be able to go to the product view from the tag index.
     */
    public function testCanGoToTaggedProduct()
    {
        $tag = $this->createTag();
        $product = $this->createProduct();
        $tag->products()->attach($product->id);

        $this->goToTagIndex()
            ->see($tag->name)
            ->see($product->sku)
            ->click($product->sku)
            ->seePageIs(route('products.show', ['sku' => $product->sku]));
    }

    /**
     * Should be able to delete a tag from the tag index.
     */
    public function testCanDeleteTag()
    {
        $tag = $this->createTag();

        $this->goToTagIndex()
            ->see($tag->name)
            ->press("delete-tag-{$tag->id}")
            ->seePageIs(route('tags.index'))
            ->see('Deleted')
            ->dontSee($tag->name);
    }

    /**
     * Should be able to set a product's tags from its view page.
     */
    public function testCanSetTagsForProductFromProductView()
    {
        $tag = $this->createTag();
        $product = $this->createProduct();
        $productView = route('products.show', ['sku' => $product->sku]);

        $this->actingAs($this->staffUser())
            ->visit($productView)
            ->seePageIs($productView)
            ->select($tag->id, 'tag-ids')
            ->press('Save tags')
            ->see('Tags updated');

        $tagOption = $this->crawler()->filter("#tag-option-{$tag->id}");
        $this->assertEquals($tagOption->attr('selected'), 'selected');

        $product = Product::where('id', '=', $product->id)
            ->with('tags')
            ->first();
        $this->assertContains($tag->id, $product->tags->pluck('id'));
    }

    /**
     * @return $this
     */
    private function goToTagIndex()
    {
        return $this->actingAs($this->staffUser())
            ->visit(route('tags.index'))
            ->seePageIs(route('tags.index'));
    }
}
