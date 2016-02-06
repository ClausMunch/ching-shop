<?php

namespace Testing\Unit\ChingShop\Catalogue\Product;

use Mockery\MockInterface;

use Testing\Unit\UnitTest;
use Testing\Unit\Behaviour\MocksModel;

use ChingShop\Http\View\Staff\HttpCrud;
use ChingShop\Catalogue\Product\Product;
use ChingShop\Catalogue\Product\ProductPresenter;

class ProductPresenterTest extends UnitTest
{
    use MocksModel;

    /** @var ProductPresenter */
    private $productPresenter;

    /** @var Product|MockInterface */
    private $product;

    /**
     * Initialise product presenter with mock product
     */
    public function setUp()
    {
        $this->product = $this->mockery(Product::class);
        $this->setMockModel($this->product);
        $this->productPresenter = new ProductPresenter($this->product);
    }

    /**
     * Sanity check for instantiation
     */
    public function testCanInstantiate()
    {
        $this->assertInstanceOf(
            ProductPresenter::class,
            $this->productPresenter
        );
        $this->assertInstanceOf(
            HttpCrud::class,
            $this->productPresenter
        );
    }

    /**
     * Should simply give underlying product SKU
     */
    public function testPassesProductSKU()
    {
        $sku = $this->generator()->anyString();
        $this->mockModelAttribute('sku', $sku);
        $this->assertSame($sku, $this->productPresenter->SKU());
    }

    /**
     * Should limit product name length to < 100 characters
     */
    public function testLimitsNameLength()
    {
        $productName = str_repeat($this->generator()->anyString(), 101);
        $this->mockModelAttribute('name', $productName);

        $this->assertSame(
            mb_strimwidth($productName, 0, 100) . '...',
            $this->productPresenter->name()
        );
    }

    /**
     * Should simply give underlying product SKU
     */
    public function testPassesID()
    {
        $id = $this->generator()->anyInteger();
        $this->mockModelAttribute('id', $id);
        $this->assertSame($id, $this->productPresenter->ID());
    }

    /**
     * Should simply give underlying product isStored
     */
    public function testPassesIsStored()
    {
        $isStored = $this->generator()->anyBoolean();
        $this->mockModelMethod('isStored', $isStored);
        $this->assertSame($isStored, $this->productPresenter->isStored());
    }

    /**
     * Should have CRUD route prefix
     */
    public function testCrudRoutePrefix()
    {
        $this->assertInternalType(
            'string',
            $this->productPresenter->crudRoutePrefix()
        );
    }

    /**
     * Should use SKU for CRUD ID
     */
    public function testCrudIDIsSKU()
    {
        $sku = $this->generator()->anyString();
        $this->mockModelAttribute('sku', $sku);
        $this->assertSame($sku, $this->productPresenter->crudID());
    }
}
