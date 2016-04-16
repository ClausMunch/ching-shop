<?php

namespace Testing\Unit\ChingShop\Catalogue\Product;

use ChingShop\Catalogue\Price\Price;
use ChingShop\Catalogue\Product\Product;
use ChingShop\Catalogue\Product\ProductPresenter;
use ChingShop\Catalogue\Product\ProductRepository;
use ChingShop\Image\Image;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
        $this->assertSame('', $emptyPresenter->sku());
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

        $presenter = $this->productRepository->presentBySku($sku);

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

        $presenter = $this->productRepository->presentBySku('foobar');

        $this->assertEmpty($presenter->id());
        $this->assertEmpty($presenter->sku());
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

        $presenter = $this->productRepository->presentById($id);

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

        $presenter = $this->productRepository->presentById(0);

        $this->assertEmpty($presenter->id());
        $this->assertEmpty($presenter->sku());
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

        $loaded = $this->productRepository->mustLoadBySku($sku);

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

        $product->expects($this->once())
            ->method('prices')
            ->willReturn($prices);
        $prices->expects($this->once())
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
        $price->expects($this->once())
            ->method('save');

        $this->productRepository->setPriceBySku('foo sku', $units, $subunits);
    }

    /**
     * Should be able to update the order of images.
     */
    public function testUpdateImageOrder()
    {
        $this->productResource->shouldReceive('where->with->limit->first')
            ->atLeast()
            ->once()
            ->andReturn($this->productResource);

        $image = $this->makeMock(Image::class);
        $image->expects($this->atLeastOnce())
            ->method('__get')
            ->with('id')
            ->willReturn(123);
        $secondImage = $this->makeMock(Image::class);
        $secondImage->expects($this->atLeastOnce())
            ->method('__get')
            ->with('id')
            ->willReturn(456);

        $this->productResource->shouldReceive('getAttribute')
            ->with('images')
            ->atLeast()
            ->once()
            ->andReturn(new Collection([$image, $secondImage]));

        $imageRelation = $this->makeMock(BelongsToMany::class);
        $this->productResource->shouldReceive('images')
            ->atLeast()
            ->once()
            ->andReturn($imageRelation);

        $imageRelation->expects($this->atLeastOnce())
            ->method('updateExistingPivot');

        $this->productRepository->updateImageOrder(789, [123 => 1]);
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
            'orderBy->has->take->get'
        )->once()->andReturn($collection);

        return $collection;
    }
}
