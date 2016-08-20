<?php

namespace Testing\Unit\ChingShop\Http\Controller\Staff;

use ChingShop\Http\Requests\Staff\Catalogue\NewImagesRequest;
use ChingShop\Http\Requests\Staff\Catalogue\Product\PersistProductRequest;
use ChingShop\Image\Image;
use ChingShop\Image\ImageRepository;
use ChingShop\Modules\Catalogue\Http\Controllers\Staff\ProductController;
use ChingShop\Modules\Catalogue\Domain\Product\Product;
use ChingShop\Modules\Catalogue\Domain\Tag\TagRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Mockery\MockInterface;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use Testing\Unit\ChingShop\Http\Controller\ControllerTest;

class ProductControllerTest extends ControllerTest
{
    /** @var ProductController */
    private $productController;

    /** @var ImageRepository|MockInterface */
    private $imageRepository;

    /** @var TagRepository|MockObject */
    private $tagRepository;

    /**
     * Initialise product controller with mock dependencies.
     */
    public function setUp()
    {
        parent::setUp();

        $this->imageRepository = $this->mockery(ImageRepository::class);
        $this->tagRepository = $this->makeMock(TagRepository::class);

        $this->productController = new ProductController(
            $this->catalogueRepository(),
            $this->webUi()
        );
    }

    /**
     * Sanity check for instantiation.
     */
    public function testConstruct()
    {
        $this->assertInstanceOf(
            ProductController::class,
            $this->productController
        );
    }

    /**
     * Should return latest products bound to index view.
     */
    public function testIndex()
    {
        $products = new Collection([]);
        $this->catalogueRepository()
            ->expects($this->atLeastOnce())
            ->method('loadLatestProducts')
            ->willReturn($products);

        $view = $this->expectViewToBeMade('catalogue::staff.products.index');

        $response = $this->productController->index();

        $this->assertSame($view, $response);
    }

    /**
     * Should render the product creation form bound with an empty product.
     */
    public function testCreate()
    {
        $view = $this->expectViewToBeMade('catalogue::staff.products.create');

        $response = $this->productController->create();

        $this->assertSame($view, $response);
    }

    /**
     * Store a new product and redirect to view of that product.
     */
    public function testStore()
    {
        /** @var PersistProductRequest|MockInterface $storeProductRequest */
        $storeProductRequest = $this->mockery(PersistProductRequest::class);

        $requestData = [];
        $storeProductRequest->shouldReceive('all')->andReturn($requestData);

        $product = $this->mockery(Product::class);
        $this->catalogueRepository()
            ->expects($this->atLeastOnce())
            ->method('createProduct')
            ->with($requestData)
            ->willReturn($product);

        $sku = $this->generator()->anyString();
        $product->shouldReceive('getAttribute')
            ->with('sku')
            ->andReturn($sku);

        $redirect = $this->mockery(RedirectResponse::class);
        $this->webUi()->expects($this->atLeastOnce())
            ->method('redirect')
            ->willReturn($redirect);

        $this->productController->store($storeProductRequest);
    }

    /**
     * Should load the product view bound with the product.
     */
    public function testShow()
    {
        $sku = $this->generator()->anyString();
        $product = $this->mockery(Product::class);
        $product->shouldReceive('isStored')->andReturn(true);
        $this->catalogueRepository()
            ->expects($this->atLeastOnce())
            ->method('loadProductBySku')
            ->willReturn($product);

        $tags = new Collection();
        $this->catalogueRepository()->expects($this->atLeastOnce())
            ->method('loadAllTags')
            ->willReturn($tags);

        $view = $this->expectViewToBeMade('catalogue::staff.products.show');

        $response = $this->productController->show($sku);

        $this->assertSame($view, $response);
    }

    /**
     * Should render the product creation form bound with the product.
     */
    public function testEdit()
    {
        $sku = $this->generator()->anyString();
        $product = $this->mockery(Product::class);
        $product->shouldReceive('isStored')->andReturn(true);
        $this->catalogueRepository()
            ->expects($this->atLeastOnce())
            ->method('loadProductBySku')
            ->willReturn($product);

        $view = $this->expectViewToBeMade('catalogue::staff.products.edit');

        $response = $this->productController->edit($sku);

        $this->assertSame($view, $response);
    }

    /**
     * Update product and redirect to view of that product.
     */
    public function testUpdate()
    {
        /** @var PersistProductRequest|MockInterface $storeProductRequest */
        $storeProductRequest = $this->mockery(PersistProductRequest::class);

        $requestData = [];
        $storeProductRequest->shouldReceive('all')->andReturn($requestData);

        $sku = $this->generator()->anyString();

        $product = $this->mockery(Product::class);
        $this->catalogueRepository()
            ->expects($this->atLeastOnce())
            ->method('updateProduct')
            ->willReturn($product);

        $product->shouldReceive('getAttribute')
            ->with('sku')
            ->andReturn($sku);

        $redirect = $this->mockery(RedirectResponse::class);
        $this->webUi()->expects($this->atLeastOnce())
            ->method('redirect')
            ->willReturn($redirect);

//        $this->mockNewImageUpload($storeProductRequest);

        $response = $this->productController->update(
            $storeProductRequest, $sku
        );

        $this->assertSame($redirect, $response);
    }

    /**
     * Should be able to make an image detachment request.
     */
    public function testDetachProductImage()
    {
        $productId = $this->generator()->anyInteger();
        $imageId = $this->generator()->anyInteger();

        $product = $this->mockery(Product::class);
        $this->catalogueRepository()
            ->expects($this->atLeastOnce())
            ->method('loadProductById')
            ->willReturn($product);

        $image = $this->makeMock(Image::class);
        $this->catalogueRepository()
            ->expects($this->atLeastOnce())
            ->method('loadImageById')
            ->willReturn($image);

        $this->catalogueRepository()
            ->expects($this->atLeastOnce())
            ->method('detachImageFromOwner');

        $sku = $this->generator()->anyString();
        $product->shouldReceive('getAttribute')
            ->with('sku')
            ->andReturn($sku);

        $redirect = $this->mockery(RedirectResponse::class);
        $this->webUi()->expects($this->atLeastOnce())
            ->method('redirect')
            ->willReturn($redirect);

        $this->imageRepository->shouldReceive('detachImage');

        $response = $this->productController->detachImage(
            $productId,
            $imageId
        );

        $this->assertInstanceOf(RedirectResponse::class, $response);
    }

    /**
     * @return View|MockInterface
     */
    private function makeMockeryView(): MockInterface
    {
        return $this->mockery(View::class);
    }

    /**
     * @param string $viewName
     *
     * @return View|MockInterface
     */
    private function expectViewToBeMade(string $viewName): MockInterface
    {
        $view = $this->makeMockeryView();
        $this->webUi()
            ->expects($this->atLeastOnce())
            ->method('view')
            ->with($viewName, $this->isType('array'))
            ->willReturn($view);

        return $view;
    }

    /**
     * @param MockInterface $storeProductRequest
     */
    private function mockNewImageUpload(MockInterface $storeProductRequest)
    {
        $storeProductRequest->shouldReceive('hasFile')
            ->with(NewImagesRequest::PARAMETER)
            ->andReturn($this->generator()->anyBoolean());
        $storeProductRequest->shouldReceive('file')
            ->with('new-image')
            ->andReturn([]);
        $this->imageRepository->shouldReceive('attachUploadedImagesToProduct');
    }
}
