<?php

namespace Testing\Functional\Staff\Products;

use Testing\Functional\Staff\StaffUser;

/**
 * Class ProductOptionTest.
 */
class ProductOptionTest extends ProductTest
{
    use StaffUser;

    /**
     * Should be able to add a new option for a product.
     *
     * @slowThreshold 800
     */
    public function testCanAddOptionForProduct()
    {
        $product = $this->createProduct();

        $newOptionLabel = str_random();
        $this->actingAs($this->staffUser())
            ->visit(route('products.show', [$product->sku]))
            ->type($newOptionLabel, 'label')
            ->press('Add option')
            ->seePageIs(route('products.show', [$product->sku]))
            ->see('Added new option')
            ->see($newOptionLabel);
    }

    /**
     * Should be able to set the label for a product option.
     */
    public function testCanSetOptionLabel()
    {
        $product = $this->createProduct();
        $option = $this->createProductOptionFor($product);

        $this->actingAs($this->staffUser())
            ->visit(route('products.show', [$product->sku]))
            ->see($option->id);

        $newOptionLabel = '';
        $this->json(
            'PUT',
            "/staff/products/{$product->id}/options/{$option->id}/label",
            [
                '_token' => $this->documentCsrfToken(),
                'label'  => $newOptionLabel,
            ]
        );

        $this->actingAs($this->staffUser())
            ->visit(route('products.show', [$product->sku]))
            ->see($newOptionLabel);
    }

    /**
     * Should be able to update the order of images for the product option.
     */
    public function testCanUpdateOptionImageOrder()
    {
        // Given we have a product option with some images;
        $product = $this->createProduct();
        $option = $this->createProductOptionFor($product);
        $images = collect([]);
        for ($i = 0; $i < 2; $i++) {
            $images->push($this->createImage());
        }
        $option->images()->sync($images->pluck('id')->all());

        // When we update the image order;
        $this->actingAs($this->staffUser())
            ->visit(route('products.show', [$product->sku]));
        $updatedImageOrder = [
            $images[1]->id => 0,
            $images[0]->id => 1,
        ];
        $this->json(
            'PUT',
            $this->getElementAttribute(
                "#owner-{$option->id}-images",
                'data-sort-action'
            ),
            [
                '_token'     => $this->documentCsrfToken(),
                'imageOrder' => $updatedImageOrder,
            ]
        );
        $this->assertEquals(200, $this->response->getStatusCode());
        $this->seeJson($updatedImageOrder);

        // That new order should be reflected on the staff product page;
        $this->actingAs($this->staffUser())
            ->visit(route('products.show', [$product->sku]));
        $imageList = $this->crawler()
            ->filter("#owner-{$option->id}-images")
            ->first()
            ->children();
        $this->assertEquals(
            $images[1]->id,
            $imageList->getNode(0)->getAttribute('data-image-id')
        );
        $this->assertEquals(
            $images[0]->id,
            $imageList->getNode(1)->getAttribute('data-image-id')
        );
    }

    /**
     * Should be able to transfer an image from a product to an image.
     */
    public function testCanTransferImageFromProductToOption()
    {
        // Given we have a product with an image and an option;
        $product = $this->createProduct();
        $image = $this->createImage();
        $product->images()->sync([$image->id]);
        $option = $this->createProductOptionFor($product);

        // When we move the image from the product to the option;
        $this->actingAs($this->staffUser())
            ->visit(route('products.show', [$product->sku]));
        $optionSortAction = $this->getElementAttribute(
            "#owner-{$option->id}-images",
            'data-sort-action'
        );
        $productSortAction = $this->getElementAttribute(
            "#owner-{$product->sku}-images",
            'data-sort-action'
        );
        $csrfToken = $this->documentCsrfToken();
        $this->json(
            'PUT',
            $optionSortAction,
            ['_token' => $csrfToken, 'imageOrder' => [$image->id => 0]]
        );
        $this->json(
            'PUT',
            $productSortAction,
            ['_token' => $csrfToken, 'imageOrder' => []]
        );

        // Then the option should have the image;
        $this->actingAs($this->staffUser())
            ->visit(route('products.show', [$product->sku]));
        $optionImages = $this->crawler()
            ->filter("#owner-{$option->id}-images")
            ->first()
            ->children();
        $this->assertEquals(
            $image->id,
            $optionImages->getNode(0)->getAttribute('data-image-id')
        );

        // And the product should not;
        $productImages = $this->crawler()
            ->filter("#owner-{$product->sku}-images")
            ->first()
            ->children();
        $this->assertEmpty($productImages->getNode(0));
    }

    /**
     * Should be able to set the supplier number for a product option.
     */
    public function testCanSetSupplierNumber()
    {
        // Given there is a product option;
        $product = $this->createProduct();
        $option = $this->createProductOptionFor($product);

        // When we visit the product staff view for that option;
        $this->actingAs($this->staffUser())
            ->visit(route('products.show', [$product->sku]))
            ->see($option->label);

        // And we set the supplier number;
        $supplierNumber = str_random();
        $this->type($supplierNumber, 'supplier-number')
            ->press("Set {$option->label} supplier number");

        // Then the supplier number should be set.
        $this->see('Set the supplier number')
            ->see($supplierNumber);
        $this->assertEquals($supplierNumber, $option->fresh()->supplier_number);
    }
}
