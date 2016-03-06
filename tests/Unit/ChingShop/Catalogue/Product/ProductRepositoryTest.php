<?php

namespace Testing\Unit\ChingShop\Catalogue\Product;

use ChingShop\Catalogue\Product\Product;
use ChingShop\Catalogue\Product\ProductPresenter;
use ChingShop\Catalogue\Product\ProductRepository;
use Illuminate\Database\Eloquent\Collection;
use Mockery\MockInterface;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
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
     * Should be able to wrap latest product collection
     * in product presenters.
     */
    public function testPresentLatest()
    {
        $collection = $this->productResourceWillLoadCollection();

        $mockProduct = $this->makeMock(Product::class);
        $collection->add($mockProduct);

        $presentation = $this->productRepository->presentLatest();
        $this->assertInternalType('array', $presentation);
        $this->assertNotEmpty($presentation);

        /** @var ProductPresenter $presenter */
        $presenter = $presentation[0];
        $this->assertInstanceOf(ProductPresenter::class, $presenter);

        $this->assertPresenterIsPresenting($presenter, $mockProduct);
    }

    /**
     * Should be able to get empty / null product presenter.
     */
    public function testPresentEmpty()
    {
        $emptyPresenter = $this->productRepository->presentEmpty();
        $this->assertSame('', $emptyPresenter->name());
        $this->assertSame('', $emptyPresenter->SKU());
        $this->assertSame(0, $emptyPresenter->id());
        $this->assertSame(false, $emptyPresenter->isStored());
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
            ->atLeast()->once()
            ->andReturn($newProduct);
        $newProduct->expects($this->atLeastOnce())
            ->method('save');

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
            ->atLeast()->once()
            ->andReturn($product);
        $product->expects($this->atLeastOnce())
            ->method('fill')
            ->with(compact('name', 'sku'));
        $product->expects($this->atLeastOnce())
            ->method('save');

        $this->productRepository->update($sku, compact('name', 'sku'));
    }

    /**
     * Should be able to get a presenter-decorated product by SKU.
     */
    public function testPresentBySKU()
    {
        $sku = $this->generator()->anyString();

        $product = $this->makeMock(Product::class);
        $this->productResource->shouldReceive('where->with->first')
            ->atLeast()->once()
            ->andReturn($product);

        $presenter = $this->productRepository->presentBySKU($sku);

        $this->assertPresenterIsPresenting($presenter, $product);
    }

    /**
     * Should give an empty presentation if the SKU does not exist.
     */
    public function testPresentsEmptyIfSKUNotFound()
    {
        $this->productResource->shouldReceive('where->with->first')
            ->atLeast()->once()
            ->andReturn(null);

        $presenter = $this->productRepository->presentBySKU('foobar');

        $this->assertEmpty($presenter->id());
        $this->assertEmpty($presenter->SKU());
        $this->assertEmpty($presenter->slug());
    }

    /**
     * Should be able to get a presenter-decorated product by ID.
     */
    public function testPresentByID()
    {
        $id = $this->generator()->anyInteger();

        $product = $this->makeMock(Product::class);
        $this->productResource->shouldReceive('where->with->first')
            ->atLeast()->once()
            ->andReturn($product);

        $presenter = $this->productRepository->presentByID($id);

        $this->assertPresenterIsPresenting($presenter, $product);
    }

    /**
     * Should give an empty presentation if the ID does not exist.
     */
    public function testPresentsEmptyIfIDNotFound()
    {
        $this->productResource->shouldReceive('where->with->first')
            ->atLeast()->once()
            ->andReturn(null);

        $presenter = $this->productRepository->presentByID(0);

        $this->assertEmpty($presenter->id());
        $this->assertEmpty($presenter->SKU());
        $this->assertEmpty($presenter->slug());
    }

    /**
     * Should load the product for the given SKU.
     */
    public function testMustLoadBySKU()
    {
        $sku = $this->generator()->anyString();
        $product = $this->makeMock(Product::class);
        $this->productResource->shouldReceive('where->limit->first')
            ->atLeast()->once()
            ->andReturn($product);

        $loaded = $this->productRepository->mustLoadBySKU($sku);

        $this->assertSame($product, $loaded);
    }

    /**
     * Should load the product for the given ID.
     */
    public function testMustLoadById()
    {
        $id = $this->generator()->anyInteger();
        $product = $this->makeMock(Product::class);
        $this->productResource->shouldReceive('where->limit->first')
            ->atLeast()->once()
            ->andReturn($product);

        $loaded = $this->productRepository->mustLoadById($id);

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
     * @param ProductPresenter $presenter
     * @param MockObject       $mockProduct
     */
    private function assertPresenterIsPresenting(
        ProductPresenter $presenter,
        MockObject $mockProduct
    ) {
        $mockProductName = $this->generator()->anyString();
        $mockProduct->expects($this->atLeastOnce())
            ->method('__get')
            ->with('name')
            ->willReturn($mockProductName);

        $this->assertSame(
            $mockProductName,
            $presenter->name(),
            'Product presenter is not giving the name of the underlying product'
        );
    }

    /**
     * @return Collection
     */
    private function productResourceWillLoadCollection()
    {
        /** @var Collection $collection */
        $collection = new Collection();
        $this->productResource->shouldReceive(
            'orderBy->take->get'
        )->once()->andReturn($collection);

        return $collection;
    }
}
