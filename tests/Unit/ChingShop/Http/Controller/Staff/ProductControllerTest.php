<?php

namespace Testing\Unit\ChingShop\Http\Controller\Staff;

use ChingShop\Catalogue\Product\Product;
use Testing\Unit\UnitTest;

use Mockery\MockInterface;

use ChingShop\Image\ImageRepository;
use ChingShop\Catalogue\Product\ProductPresenter;
use ChingShop\Catalogue\Product\ProductRepository;
use ChingShop\Http\Requests\PersistProductRequest;
use ChingShop\Http\Controllers\Staff\ProductController;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Contracts\Routing\ResponseFactory;

class ProductControllerTest extends UnitTest
{
    /** @var ProductController */
    private $productController;

    /** @var ProductRepository|MockInterface */
    private $productRepository;

    /** @var ViewFactory|MockInterface */
    private $viewFactory;

    /** @var ResponseFactory|MockInterface */
    private $responseFactory;

    /** @var ImageRepository|MockInterface */
    private $imageRepository;

    /**
     * Initialise product controller with mock dependencies
     */
    public function setUp()
    {
        parent::setUp();

        $this->productRepository = $this->mockery(ProductRepository::class);
        $this->viewFactory = $this->mockery(ViewFactory::class);
        $this->responseFactory = $this->mockery(ResponseFactory::class);
        $this->imageRepository = $this->mockery(ImageRepository::class);

        $this->productController = new ProductController(
            $this->productRepository,
            $this->viewFactory,
            $this->responseFactory,
            $this->imageRepository
        );
    }

    /**
     * Sanity check for instantiation
     */
    public function testConstruct()
    {
        $this->assertInstanceOf(
            ProductController::class,
            $this->productController
        );
    }

    /**
     * Should return latest products bound to index view
     */
    public function testIndex()
    {
        $products = [];
        $this->productRepository->shouldReceive('presentLatest')
            ->andReturn($products);

        $view = $this->expectViewToBeMadeWith(
            'staff.products.index',
            compact('products')
        );

        $response = $this->productController->index();

        $this->assertSame($view, $response);
    }

    /**
     * Should render the product creation form bound with an empty product
     */
    public function testCreate()
    {
        $product = $this->mockery(ProductPresenter::class);
        $this->productRepository->shouldReceive('presentEmpty')
            ->andReturn($product);

        $view = $this->expectViewToBeMadeWith(
            'staff.products.create',
            compact('product')
        );

        $response = $this->productController->create();

        $this->assertSame($view, $response);
    }

    /**
     * Store a new product and redirect to view of that product
     */
    public function testStore()
    {
        /** @var PersistProductRequest|MockInterface $storeProductRequest */
        $storeProductRequest = $this->mockery(PersistProductRequest::class);

        $requestData = [];
        $storeProductRequest->shouldReceive('all')->andReturn($requestData);
        $this->mockNewImageUpload($storeProductRequest);

        $product = $this->mockery(Product::class);
        $this->productRepository->shouldReceive('create')
            ->with($requestData)
            ->andReturn($product);

        $SKU = $this->generator()->anyString();
        $product->shouldReceive('getAttribute')
            ->with('sku')
            ->andReturn($SKU);

        $redirect = $this->mockery(RedirectResponse::class);
        $this->responseFactory->shouldReceive('redirectToRoute')
            ->with(
                'staff.products.show',
                ['sku' => $SKU]
            )
            ->andReturn($redirect);

        $response = $this->productController->store($storeProductRequest);

        $this->assertSame($redirect, $response);
    }

    /**
     * Should load the product view bound with the product
     */
    public function testShow()
    {
        $SKU = $this->generator()->anyString();
        $product = $this->mockery(ProductPresenter::class);
        $product->shouldReceive('isStored')->andReturn(true);
        $this->productRepository->shouldReceive('presentBySKU')
            ->with($SKU)
            ->andReturn($product);

        $view = $this->expectViewToBeMadeWith(
            'staff.products.show',
            compact('product')
        );

        $response = $this->productController->show($SKU);

        $this->assertSame($view, $response);
    }

    /**
     * Should render the product creation form bound with the product
     */
    public function testEdit()
    {
        $SKU = $this->generator()->anyString();
        $product = $this->mockery(ProductPresenter::class);
        $product->shouldReceive('isStored')->andReturn(true);
        $this->productRepository->shouldReceive('presentBySKU')
            ->with($SKU)
            ->andReturn($product);

        $view = $this->expectViewToBeMadeWith(
            'staff.products.edit',
            compact('product')
        );

        $response = $this->productController->edit($SKU);

        $this->assertSame($view, $response);
    }

    /**
     * Update product and redirect to view of that product
     */
    public function testUpdate()
    {
        /** @var PersistProductRequest|MockInterface $storeProductRequest */
        $storeProductRequest = $this->mockery(PersistProductRequest::class);

        $requestData = [];
        $storeProductRequest->shouldReceive('all')->andReturn($requestData);

        $SKU = $this->generator()->anyString();

        $product = $this->mockery(Product::class);
        $this->productRepository->shouldReceive('update')
            ->with($SKU, $requestData)
            ->andReturn($product);

        $product->shouldReceive('getAttribute')
            ->with('sku')
            ->andReturn($SKU);

        $redirect = $this->mockery(RedirectResponse::class);
        $this->responseFactory->shouldReceive('redirectToRoute')
            ->with(
                'staff.products.show',
                ['sku' => $SKU]
            )
            ->andReturn($redirect);

        $this->mockNewImageUpload($storeProductRequest);

        $response = $this->productController->update(
            $storeProductRequest, $SKU
        );

        $this->assertSame($redirect, $response);
    }

    /**
     * @return View|MockInterface
     */
    private function makeMockView(): MockInterface
    {
        return $this->mockery(View::class);
    }

    /**
     * @param string $viewName
     * @param array $bindData
     * @return View|MockInterface
     */
    private function expectViewToBeMadeWith(
        string $viewName,
        array $bindData
    ): MockInterface {
        $view = $this->makeMockView();
        $this->viewFactory->shouldReceive('make')
            ->with($viewName, $bindData)
            ->andReturn($view);
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
