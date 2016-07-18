<?php

namespace Testing\Unit\ChingShop\Catalogue\Product;

use ChingShop\Modules\Catalogue\Model\Price\Price;
use ChingShop\Modules\Catalogue\Model\Product\Product;
use ChingShop\Modules\Catalogue\Model\Product\ProductRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Mockery\MockInterface;
use Testing\Unit\Behaviour\MocksModel;
use Testing\Unit\UnitTest;

class ProductRepositoryTest extends UnitTest
{
    use MocksModel;

    /** @var ProductRepository */
    private $productRepository;

    /** @var Product|MockInterface */
    private $productResource;

    /**
     * Initialise product repository with mock product resource model.
     */
    public function setUp()
    {
        parent::setUp();
        $this->productResource = $this->mockery(Product::class);
        $this->setMockModel($this->productResource);
        $this->productRepository = new ProductRepository(
            $this->productResource
        );
    }

    /**
     * Sanity check for instantiation.
     */
    public function testCanInstantiate()
    {
        $this->assertInstanceOf(
            ProductRepository::class,
            $this->productRepository
        );
    }

    /**
     * Sanity check loading latest product resource models.
     */
    public function testLoadLatest()
    {
        $collection = $this->productResourceWillLoadCollection();
        $this->assertSame($collection, $this->productRepository->loadLatest());
    }

    /**
     * Should be able to create with given data.
     */
    public function testCreate()
    {
        $name = $this->generator()->anyString();
        $sku = $this->generator()->anyString();

        $newProduct = $this->makeMock(Product::class);
        $this->productResource->shouldReceive('create')
            ->with(compact('name', 'sku'))
            ->atLeast()
            ->once()
            ->andReturn($newProduct);

        $this->productRepository->create(compact('name', 'sku'));
    }

    /**
     * Should be able to update product data.
     */
    public function testUpdate()
    {
        $name = $this->generator()->anyString();
        $sku = $this->generator()->anyString();

        $product = $this->makeMock(Product::class);
        $this->productResource->shouldReceive('where->firstOrFail')
            ->atLeast()
            ->once()
            ->andReturn($product);
        $product->expects($this->atLeastOnce())
            ->method('fill')
            ->with(compact('name', 'sku'));
        $product->expects($this->atLeastOnce())
            ->method('save');

        $this->productRepository->update($sku, compact('name', 'sku'));
    }

    /**
     * Should load the product for the given SKU.
     */
    public function testLoadBySKU()
    {
        $sku = $this->generator()->anyString();
        $product = $this->makeMock(Product::class);
        $this->productResource->shouldReceive('where->with->first')
            ->atLeast()
            ->once()
            ->andReturn($product);

        $loaded = $this->productRepository->loadBySku($sku);

        $this->assertSame($product, $loaded);
    }

    /**
     * Should load the product for the given ID.
     */
    public function testLoadById()
    {
        $id = $this->generator()->anyInteger();
        $product = $this->makeMock(Product::class);
        $this->productResource->shouldReceive('where->with->first')
            ->atLeast()
            ->once()
            ->andReturn($product);

        $loaded = $this->productRepository->loadById($id);

        $this->assertSame($product, $loaded);
    }

    /**
     * Should delete the product with the given SKU.
     */
    public function testDeleteBySku()
    {
        $sku = $this->generator()->anyString();
        $this->productResource->shouldReceive('where->limit->first->delete')
            ->atLeast()->once()
            ->andReturn(true);

        $deleted = $this->productRepository->deleteBySku($sku);

        $this->assertTrue($deleted);
    }

    /**
     * Should be able to set the price by SKU.
     */
    public function testSetPriceBySku()
    {
        $product = $this->makeMock(Product::class);
        $this->productResource->shouldReceive('where->with->limit->first')
            ->atLeast()->once()
            ->andReturn($product);

        $prices = $this->makeMock(HasMany::class);
        $price = $this->makeMock(Price::class);

        $product->expects($this->atLeastOnce())
            ->method('prices')
            ->willReturn($prices);
        $prices->expects($this->atLeastOnce())
            ->method('firstOrNew')
            ->willReturn($price);

        $units = 5;
        $subunits = 99;
        $price->expects($this->atLeastOnce())
            ->method('setAttribute')
            ->withConsecutive(
                ['units', $units],
                ['subunits', $subunits],
                ['currency', 'GBP']
            );
        $price->expects($this->atLeastOnce())
            ->method('save');

        $this->productRepository->setPriceBySku('foo sku', $units, $subunits);
    }

    /**
     * @return Collection
     */
    private function productResourceWillLoadCollection()
    {
        /** @var Collection $collection */
        $collection = new Collection();
        $this->productResource
            ->shouldReceive(
                'orderBy->has->with->limit->get'
            )
            ->once()
            ->andReturn($collection);

        return $collection;
    }
}
