<?php

namespace Testing\Unit\ChingShop\Catalogue\Product;

use ChingShop\Catalogue\Product\Product;
use ChingShop\Catalogue\Product\ProductPresenter;
use ChingShop\Http\View\Staff\HttpCrudInterface;
use Illuminate\Support\Collection;
use Mockery\MockInterface;
use Testing\Unit\Behaviour\MocksModel;
use Testing\Unit\UnitTest;

class ProductPresenterTest extends UnitTest
{
    use MocksModel;

    /** @var ProductPresenter */
    private $productPresenter;

    /** @var Product|MockInterface */
    private $product;

    /**
     * Initialise product presenter with mock product.
     */
    public function setUp()
    {
        $this->product = $this->mockery(Product::class);
        $this->setMockModel($this->product);
        $this->productPresenter = new ProductPresenter($this->product);
    }

    /**
     * Sanity check for instantiation.
     */
    public function testCanInstantiate()
    {
        $this->assertInstanceOf(
            ProductPresenter::class,
            $this->productPresenter
        );
        $this->assertInstanceOf(
            HttpCrudInterface::class,
            $this->productPresenter
        );
    }

    /**
     * Should simply give underlying product SKU.
     */
    public function testPassesProductSKU()
    {
        $sku = $this->generator()->anyString();
        $this->mockModelAttribute('sku', $sku);
        $this->assertSame($sku, $this->productPresenter->SKU());
    }

    /**
     * Should limit product name length to < 100 characters.
     */
    public function testLimitsNameLength()
    {
        $productName = str_repeat($this->generator()->anyString(), 101);
        $this->mockModelAttribute('name', $productName);

        $this->assertSame(
            mb_strimwidth($productName, 0, 100).'...',
            $this->productPresenter->name()
        );
    }

    /**
     * Should simply give underlying product SKU.
     */
    public function testPassesID()
    {
        $id = $this->generator()->anyInteger();
        $this->mockModelAttribute('id', $id);
        $this->assertSame($id, $this->productPresenter->id());
    }

    /**
     * Should give the underlying product slug.
     */
    public function testPassesSlug()
    {
        $slug = $this->generator()->anySlug();
        $this->mockModelAttribute('slug', $slug);
        $this->assertSame($slug, $this->productPresenter->slug());
    }

    /**
     * Should give the underlying product description.
     */
    public function testPassesDescription()
    {
        $description = $this->generator()->anyString();
        $this->mockModelAttribute('description', $description);
        $this->assertSame($description, $this->productPresenter->description());
    }

    /**
     * Should give the underlying product images.
     */
    public function testPassesImages()
    {
        $images = ['foo image'];
        $this->mockModelAttribute('images', $images);
        $this->assertSame($images, $this->productPresenter->images());
    }

    /**
     * mainImage() should give the product's first image.
     */
    public function testMainImageIsFirstImage()
    {
        $images = new Collection(['foo image', 'bar image']);
        $this->mockModelAttribute('images', $images);
        $this->assertSame($images[0], $this->productPresenter->mainImage());
    }

    /**
     * otherImages() should give all but the first image.
     */
    public function testOtherImagesIsAllButFirstImage()
    {
        $images = new Collection(['foo', 'bar', 'another']);
        $this->mockModelAttribute('images', $images);
        $this->assertEquals(
            ['bar', 'another'],
            array_values($this->productPresenter->otherImages()->all())
        );
    }

    /**
     * Location parts should be the product's ID and slug.
     */
    public function testGivesProductIdAndSlugForLocationParts()
    {
        $id = $this->generator()->anyInteger();
        $this->mockModelAttribute('id', $id);
        $slug = $this->generator()->anySlug();
        $this->mockModelAttribute('slug', $slug);

        $this->assertEquals(
            [
                'ID'   => $id,
                'slug' => $slug,
            ],
            $this->productPresenter->locationParts()
        );
    }

    /**
     * Should simply give underlying product isStored.
     */
    public function testPassesIsStored()
    {
        $isStored = $this->generator()->anyBoolean();
        $this->mockModelMethod('isStored', $isStored);
        $this->assertSame($isStored, $this->productPresenter->isStored());
    }

    /**
     * Should have CRUD route prefix.
     */
    public function testCrudRoutePrefix()
    {
        $this->assertInternalType(
            'string',
            $this->productPresenter->routePath()
        );
    }

    /**
     * Should use SKU for CRUD ID.
     */
    public function testCrudIDIsSKU()
    {
        $sku = $this->generator()->anyString();
        $this->mockModelAttribute('sku', $sku);
        $this->assertSame($sku, $this->productPresenter->crudID());
    }
}
