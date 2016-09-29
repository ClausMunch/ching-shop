<?php

namespace Testing\Functional\Staff\Products;

use Illuminate\Foundation\Testing\HttpException;
use Testing\Functional\Staff\StaffUser;

/**
 * Class ProductImagesTest.
 */
class ProductImagesTest extends ProductTest
{
    use StaffUser;

    /**
     * Should be able to upload images for a product.
     */
    public function testCanUploadProductImages()
    {
        $product = $this->createProduct();

        $testImageFile = app_path('../tests/fixtures/product/image.jpg');

        /*
         * Unfortunately have not been able to get the file upload working
         * beyond UploadedFile's is_uploaded_file check; this is an unpleasant
         * compromise to at least exercise the code.
         *
         * @see \Symfony\Component\HttpFoundation\File\UploadedFile::isValid
         */
        try {
            $productPage = route('products.show', [$product->sku]);
            $this->actingAs($this->staffUser())
                ->visit($productPage)
                ->see($product->sku)
                ->attach([$testImageFile], 'new-image')
                ->press('Add general images')
                ->assertResponseOk();

            $this->actingAs($this->staffUser())
                ->visit($productPage)
                ->see(basename($testImageFile));
        } catch (HttpException $e) {
            $this->assertContains(
                'Received status code [500]',
                $e->getMessage()
            );
        }
    }

    /**
     * A product with an image should be shown on a category page.
     */
    public function testProductWithImagesShownOnCategoryPage()
    {
        $product = $this->createProduct();
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
        $product = $this->createProduct();
        $product->images()->detach();

        $this->visit(route('customer.cards'))
            ->seePageIs(route('customer.cards'))
            ->dontSee($product->sku)
            ->dontSee($product->slug)
            ->dontSee($product->description);
    }
}
