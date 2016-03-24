<?php

namespace Testing\Unit\ChingShop\Http\Controller\Customer;

use ChingShop\Catalogue\Product\ProductPresenter;
use ChingShop\Http\Controllers\Customer\ProductController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Testing\Unit\ChingShop\Http\Controller\ControllerTest;

class ProductControllerTest extends ControllerTest
{
    /** @var ProductController */
    private $productController;

    /**
     * Set up product controller with mock dependencies for each test.
     */
    public function setUp()
    {
        $this->productController = new ProductController(
            $this->productRepository(),
            $this->viewFactory(),
            $this->responseFactory()
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
     * Should retrieve the product and pass to view factory.
     */
    public function testViewAction()
    {
        $id = abs($this->generator()->anyInteger()) + 1;
        $slug = $this->generator()->anySlug();

        $product = $this->makeMock(ProductPresenter::class);
        $product->expects($this->atLeastOnce())
            ->method('ID')
            ->willReturn($id);
        $product->expects($this->atLeastOnce())
            ->method('slug')
            ->willReturn($slug);

        $this->productRepository()->expects($this->once())
            ->method('presentByID')
            ->with($id)
            ->willReturn($product);

        $view = 'foo view';
        $this->viewFactory()->expects($this->once())
            ->method('make')
            ->with(
                'customer.product.view',
                [
                    'product' => $product,
                ]
            )
            ->willReturn($view);

        $response = $this->productController->viewAction($id, $slug);

        $this->assertEquals($view, $response);
    }

    /**
     * Should throw an exception if no product exists with the given id.
     */
    public function testThrowsExceptionIfProductDoesNotExist()
    {
        $product = $this->makeMock(ProductPresenter::class);
        $product->expects($this->atLeastOnce())
            ->method('ID')
            ->willReturn(0);

        $id = $this->generator()->anyInteger();

        $this->productRepository()->expects($this->once())
            ->method('presentByID')
            ->with($id)
            ->willReturn($product);

        $this->setExpectedExceptionRegExp(NotFoundHttpException::class, '');

        $this->productController->viewAction($id, 'foo slug');
    }

    /**
     * Should redirect to the correct url if the id and sku don't match.
     */
    public function testRedirectsOnIdSkuMisMatch()
    {
        $id = $this->generator()->anyInteger();
        $correctSlug = $this->generator()->anySlug();

        $product = $this->makeMock(ProductPresenter::class);
        $product->expects($this->atLeastOnce())
            ->method('ID')
            ->willReturn($id);
        $product->expects($this->atLeastOnce())
            ->method('slug')
            ->willReturn($correctSlug);

        $this->productRepository()->expects($this->once())
            ->method('presentByID')
            ->with($id)
            ->willReturn($product);

        $redirect = 'foo redirect';
        $this->responseFactory()->expects($this->once())
            ->method('redirectToRoute')
            ->with(
                'product::view',
                [
                    'id'   => $id,
                    'slug' => $correctSlug,
                ],
                301
            )
            ->willReturn($redirect);

        $response = $this->productController->viewAction(
            $id,
            $this->generator()->anyStringOtherThan($correctSlug)
        );

        $this->assertEquals($redirect, $response);
    }
}
