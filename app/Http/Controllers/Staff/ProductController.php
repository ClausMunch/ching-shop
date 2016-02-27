<?php

namespace ChingShop\Http\Controllers\Staff;

use ChingShop\Catalogue\Product\Product;
use ChingShop\Catalogue\Product\ProductPresenter;
use ChingShop\Catalogue\Product\ProductRepository;
use ChingShop\Http\Controllers\Controller;
use ChingShop\Http\Requests\PersistProductRequest;
use ChingShop\Image\ImageRepository;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductController extends Controller
{
    const IMAGE_UPLOAD_PARAMETER = 'new-image';

    /** @var ProductRepository */
    private $productRepository;

    /** @var ViewFactory */
    private $viewFactory;

    /** @var ResponseFactory */
    private $responseFactory;

    /** @var ImageRepository */
    private $imageRepository;

    /**
     * ProductController constructor.
     *
     * @param ProductRepository $productRepository
     * @param ViewFactory       $viewFactory
     * @param ResponseFactory   $responseFactory
     * @param ImageRepository   $imageRepository
     */
    public function __construct(
        ProductRepository $productRepository,
        ViewFactory $viewFactory,
        ResponseFactory $responseFactory,
        ImageRepository $imageRepository
    ) {
        $this->productRepository = $productRepository;
        $this->viewFactory = $viewFactory;
        $this->responseFactory = $responseFactory;
        $this->imageRepository = $imageRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = $this->productRepository->presentLatest();

        return $this->buildView('index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $product = $this->productRepository->presentEmpty();

        return $this->buildView('create', compact('product'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param PersistProductRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(PersistProductRequest $request)
    {
        $product = $this->productRepository->create($request->all());
        $this->persistUploadedImages($request, $product);

        return $this->redirectToShowProduct($product->sku);
    }

    /**
     * Display the specified resource.
     *
     * @param string $sku
     *
     * @throws NotFoundHttpException
     *
     * @return \Illuminate\Http\Response
     */
    public function show(string $sku)
    {
        $product = $this->mustPresentProductBySKU($sku);

        return $this->buildView('show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param string $sku
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(string $sku)
    {
        $product = $this->mustPresentProductBySKU($sku);

        return $this->buildView('edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param PersistProductRequest $request
     * @param string                $sku
     *
     * @return \Illuminate\Http\Response
     */
    public function update(PersistProductRequest $request, string $sku)
    {
        $product = $this->productRepository->update($sku, $request->all());
        $this->persistUploadedImages($request, $product);

        return $this->redirectToShowProduct($product->sku);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $sku
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(string $sku)
    {
        $this->productRepository->deleteBySKU($sku);

        return $this->responseFactory->redirectToRoute('staff.products.index');
    }

    /**
     * @param $name
     * @param array $bindData
     *
     * @return View
     */
    private function buildView($name, array $bindData = []): View
    {
        return $this->viewFactory->make(
            'staff.products.'.$name,
            $bindData
        );
    }

    /**
     * @param string $sku
     *
     * @return ProductPresenter
     */
    private function mustPresentProductBySKU(string $sku): ProductPresenter
    {
        $product = $this->productRepository->presentBySKU($sku);
        if (!$product->isStored()) {
            throw new NotFoundHttpException();
        }

        return $product;
    }

    /**
     * @param string $sku
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    private function redirectToShowProduct(string $sku): RedirectResponse
    {
        return $this->responseFactory->redirectToRoute(
            'staff.products.show',
            ['sku' => $sku]
        );
    }

    /**
     * @param PersistProductRequest $request
     * @param Product               $product
     */
    private function persistUploadedImages(
        PersistProductRequest $request,
        Product $product
    ) {
        if (!$request->hasFile(self::IMAGE_UPLOAD_PARAMETER)) {
            return;
        }

        $this->imageRepository->attachUploadedImagesToProduct(
            $request->file(self::IMAGE_UPLOAD_PARAMETER),
            $product
        );
    }
}
