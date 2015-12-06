<?php

namespace Testing\Unit\ChingShop\Catalogue\Product;

use Mockery\MockInterface;

use Testing\Unit\UnitTest;
use Testing\Unit\Behaviour\MocksModel;

use Illuminate\Database\Eloquent\Collection;

use ChingShop\Catalogue\Product\Product;
use ChingShop\Catalogue\Product\ProductPresenter;
use ChingShop\Catalogue\Product\ProductRepository;

class ProductRepositoryTest extends UnitTest
{
    use MocksModel;

    /** @var ProductRepository */
    private $productRepository;

    /** @var Product|MockInterface */
    private $productResource;

    /**
     * Initialise product repository with mock product resource model
     */
    public function setUp()
    {
        parent::setUp();
        $this->productResource = $this->makeMock(Product::class);
        $this->setMockModel($this->productResource);
        $this->productRepository = new ProductRepository(
            $this->productResource
        );
    }

    /**
     * Sanity check for instantiation
     */
    public function testCanInstantiate()
    {
        $this->assertInstanceOf(
            ProductRepository::class,
            $this->productRepository
        );
    }

    /**
     * Sanity check loading latest product resource models
     */
    public function testLoadLatest()
    {
        $collection = $this->makeMock(Collection::class);
        $this->productResource->shouldReceive(
            'orderBy->take->get'
        )->andReturn($collection);
        $this->assertSame($collection, $this->productRepository->loadLatest());
    }

    /**
     * Should be able to wrap latest product collection
     * in product presenters
     */
    public function testPresentLatest()
    {
        $collection = $this->makeMock(Collection::class);
        $this->productResource->shouldReceive(
            'orderBy->take->get'
        )->andReturn($collection);

        $mockProduct = $this->makeMock(Product::class);
        $collection->shouldReceive('all')->andReturn([$mockProduct]);

        $presentation = $this->productRepository->presentLatest();
        $this->assertInternalType('array', $presentation);
        $this->assertNotEmpty($presentation);

        /** @var ProductPresenter $presenter */
        $presenter = $presentation[0];
        $this->assertInstanceOf(ProductPresenter::class, $presenter);

        $this->assertPresenterIsPresenting($presenter, $mockProduct);
    }

    /**
     * Should be able to get empty / null product presenter
     */
    public function testPresentEmpty()
    {
        $emptyPresenter = $this->productRepository->presentEmpty();
        $this->assertSame('', $emptyPresenter->name());
        $this->assertSame('', $emptyPresenter->SKU());
        $this->assertSame(0, $emptyPresenter->ID());
        $this->assertSame(false, $emptyPresenter->isStored());
    }

    /**
     * Should be able to create with given data
     */
    public function testCreate()
    {
        $name = $this->generator()->anyString();
        $sku = $this->generator()->anyString();

        $newProduct = $this->makeMock(Product::class);
        $this->productResource->shouldReceive('create')
            ->with(compact('name', 'sku'))
            ->andReturn($newProduct);
        $newProduct->shouldReceive('save');

        $this->productRepository->create(compact('name', 'sku'));
    }

    /**
     * Should be able to update product data
     */
    public function testUpdate()
    {
        $name = $this->generator()->anyString();
        $sku = $this->generator()->anyString();

        $product = $this->makeMock(Product::class);
        $this->productResource->shouldReceive('where->firstOrFail')
            ->andReturn($product);
        $product->shouldReceive('fill')
            ->with(compact('name', 'sku'));
        $product->shouldReceive('save');

        $this->productRepository->update($sku, compact('name', 'sku'));
    }

    /**
     * Should be able to get a presenter-decorated product by SKU
     */
    public function testPresentBySKU()
    {
        $sku = $this->generator()->anyString();

        $product = $this->makeMock(Product::class);
        $this->productResource->shouldReceive('where->first')
            ->andReturn($product);

        $presenter = $this->productRepository->presentBySKU($sku);

        $this->assertPresenterIsPresenting($presenter, $product);
    }

    /**
     * @param ProductPresenter $presenter
     * @param MockInterface $mockProduct
     */
    private function assertPresenterIsPresenting(
        ProductPresenter $presenter,
        MockInterface $mockProduct
    ) {
        $mockProductName = $this->generator()->anyString();
        $mockProduct->shouldReceive('getAttribute')
            ->with('name')
            ->andReturn($mockProductName);

        $this->assertSame($mockProductName, $presenter->name());
    }
}