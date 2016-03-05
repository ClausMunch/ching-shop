<?php

namespace Testing\Unit\ChingShop\Http\Controller\Staff;

use ChingShop\Catalogue\Product\Product;
use ChingShop\Catalogue\Product\ProductPresenter;
use ChingShop\Http\Controllers\Staff\ProductController;
use ChingShop\Http\Requests\PersistProductRequest;
use ChingShop\Image\ImageRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Mockery\MockInterface;
use Testing\Unit\ChingShop\Http\Controller\ControllerTest;

class ProductControllerTest extends ControllerTest
{
    /** @var ProductController */
    private $productController;

    /** @var ImageRepository|MockInterface */
    private $imageRepository;

    /**
     * Initialise product controller with mock dependencies.
     */
    public function setUp()
    {
        parent::setUp();

        $this->imageRepository = $this->mockery(ImageRepository::class);

        $this->productController = new ProductController(
            $this->productRepository(),
            $this->viewFactory(),
            $this->responseFactory(),
            $this->imageRepository
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
        $products = [];
        $this->productRepository()->expects($this->atLeastOnce())
            ->method('presentLatest')
            ->willReturn($products);

        $view = $this->expectViewToBeMadeWith(
            'staff.products.index',
            compact('products')
        );

        $response = $this->productController->index();

        $this->assertSame($view, $response);
    }

    /**
     * Should render the product creation form bound with an empty product.
     */
    public function testCreate()
    {
        $product = $this->mockery(ProductPresenter::class);
        $this->productRepository()->expects($this->atLeastOnce())
            ->method('presentEmpty')
            ->willReturn($product);

        $view = $this->expectViewToBeMadeWith(
            'staff.products.create',
            compact('product')
        );

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
        $this->mockNewImageUpload($storeProductRequest);

        $product = $this->mockery(Product::class);
        $this->productRepository()->expects($this->atLeastOnce())
            ->method('create')
            ->with($requestData)
            ->willReturn($product);

        $sku = $this->generator()->anyString();
        $product->shouldReceive('getAttribute')
            ->with('sku')
            ->andReturn($sku);

        $redirect = $this->mockery(RedirectResponse::class);
        $this->responseFactory()->expects($this->atLeastOnce())
            ->method('redirectToRoute')
            ->with(
                'staff.products.show',
                ['sku' => $sku]
            )
            ->willReturn($redirect);

        $response = $this->productController->store($storeProductRequest);

        $this->assertSame($redirect, $response);
    }

    /**
     * Should load the product view bound with the product.
     */
    public function testShow()
    {
        $sku = $this->generator()->anyString();
        $product = $this->mockery(ProductPresenter::class);
        $product->shouldReceive('isStored')->andReturn(true);
        $this->productRepository()->expects($this->atLeastOnce())
            ->method('presentBySKU')
            ->with($sku)
            ->willReturn($product);

        $view = $this->expectViewToBeMadeWith(
            'staff.products.show',
            compact('product')
        );

        $response = $this->productController->show($sku);

        $this->assertSame($view, $response);
    }

    /**
     * Should render the product creation form bound with the product.
     */
    public function testEdit()
    {
        $sku = $this->generator()->anyString();
        $product = $this->mockery(ProductPresenter::class);
        $product->shouldReceive('isStored')->andReturn(true);
        $this->productRepository()->expects($this->atLeastOnce())
            ->method('presentBySKU')
            ->with($sku)
            ->willReturn($product);

        $view = $this->expectViewToBeMadeWith(
            'staff.products.edit',
            compact('product')
        );

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
        $this->productRepository()->expects($this->atLeastOnce())
            ->method('update')
            ->with($sku, $requestData)
            ->willReturn($product);

        $product->shouldReceive('getAttribute')
            ->with('sku')
            ->andReturn($sku);

        $redirect = $this->mockery(RedirectResponse::class);
        $this->responseFactory()->expects($this->atLeastOnce())
            ->method('redirectToRoute')
            ->with(
                'staff.products.show',
                ['sku' => $sku]
            )
            ->willReturn($redirect);

        $this->mockNewImageUpload($storeProductRequest);

        $response = $this->productController->update(
            $storeProductRequest, $sku
        );

        $this->assertSame($redirect, $response);
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
     * @param array  $bindData
     *
     * @return View|MockInterface
     */
    private function expectViewToBeMadeWith(
        string $viewName,
        array $bindData
    ): MockInterface {
        $view = $this->makeMockeryView();
        $this->viewFactory()->expects($this->atLeastOnce())
            ->method('make')
            ->with($viewName, $bindData)
            ->willReturn($view);

        return $view;
    }

    /**
     * @param MockInterface $storeProductRequest
     */
    private function mockNewImageUpload(MockInterface $storeProductRequest)
    {
        $storeProductRequest->shouldReceive('hasFile')
            ->with(ProductController::IMAGE_UPLOAD_PARAMETER)
            ->andReturn($this->generator()->anyBoolean());
        $storeProductRequest->shouldReceive('file')
            ->with('new-image')
            ->andReturn([]);
        $this->imageRepository->shouldReceive('attachUploadedImagesToProduct');
    }
}
