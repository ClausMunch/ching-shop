<?php

namespace ChingShop\Http\Controllers\Staff;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Contracts\Routing\ResponseFactory;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use ChingShop\Http\Requests;
use ChingShop\Http\Controllers\Controller;
use ChingShop\Http\Requests\PersistProductRequest;

use ChingShop\Catalogue\Product\ProductPresenter;
use ChingShop\Catalogue\Product\ProductRepository;

class ProductController extends Controller
{
    /** @var ProductRepository */
    private $productRepository;

    /** @var ViewFactory */
    private $viewFactory;

    /** @var ResponseFactory */
    private $responseFactory;

    /**
     * ProductController constructor.
     * @param ProductRepository $productRepository
     * @param ViewFactory $viewFactory
     * @param ResponseFactory $responseFactory
     */
    public function __construct(
        ProductRepository $productRepository,
        ViewFactory $viewFactory,
        ResponseFactory $responseFactory
    ) {
        $this->productRepository = $productRepository;
        $this->viewFactory = $viewFactory;
        $this->responseFactory = $responseFactory;
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
     * @param  PersistProductRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(PersistProductRequest $request)
    {
        $product = $this->productRepository->create($request->all());
        return $this->redirectToShowProduct($product->sku);
    }

    /**
     * Display the specified resource.
     *
     * @param  string $sku
     * @return \Illuminate\Http\Response
     * @throws NotFoundHttpException
     */
    public function show(string $sku)
    {
        $product = $this->mustPresentProductBySKU($sku);
        return $this->buildView('show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string $sku
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
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $sku
     * @return \Illuminate\Http\Response
     */
    public function update(PersistProductRequest $request, string $sku)
    {
        $product = $this->productRepository->update($sku, $request->all());
        return $this->redirectToShowProduct($product->sku);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * @param $name
     * @param array $bindData
     * @return View
     */
    private function buildView($name, array $bindData = []): View
    {
        return $this->viewFactory->make(
            'staff.products.' . $name,
            $bindData
        );
    }

    /**
     * @param string $sku
     * @return ProductPresenter
     */
    private function mustPresentProductBySKU(string $sku): ProductPresenter
    {
        $product = $this->productRepository->presentBySKU($sku);
        if (!$product->isStored()) {
            throw new NotFoundHttpException;
        }
        return $product;
    }

    /**
     * @param string $sku
     * @return \Illuminate\Http\RedirectResponse
     */
    private function redirectToShowProduct(string $sku): RedirectResponse
    {
        return $this->responseFactory->redirectToRoute(
            'staff.products.show',
            ['sku' => $sku]
        );
    }
}
