<?php

namespace ChingShop\Modules\Catalogue\Http\Controllers\Staff;

use ChingShop\Http\Controllers\Controller;
use ChingShop\Http\Requests\Staff\Catalogue\ImageOrderRequest;
use ChingShop\Http\Requests\Staff\Catalogue\Product\NewProductOptionRequest;
use ChingShop\Http\Requests\Staff\Catalogue\Product\Option\PutOptionColour;
use ChingShop\Http\Requests\Staff\Catalogue\Product\Option\PutOptionLabel;
use ChingShop\Http\WebUi;
use ChingShop\Modules\Catalogue\Domain\CatalogueRepository;
use ChingShop\Modules\Catalogue\Domain\Product\ProductOption;
use Illuminate\Http\JsonResponse;

/**
 * Class ProductOptionController.
 */
class ProductOptionController extends Controller
{
    /** @var CatalogueRepository */
    private $catalogueRepository;

    /** @var WebUi */
    private $webUi;

    /**
     * ProductOptionsController constructor.
     *
     * @param CatalogueRepository $catalogueRepository
     * @param WebUi               $webUi
     */
    public function __construct(
        CatalogueRepository $catalogueRepository,
        WebUi $webUi
    ) {
        $this->catalogueRepository = $catalogueRepository;
        $this->webUi = $webUi;
    }

    /**
     * @param NewProductOptionRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function postNew(NewProductOptionRequest $request)
    {
        $product = $this->catalogueRepository->loadProductById(
            $request->productId()
        );

        $option = $this->catalogueRepository->addOptionForProduct(
            $product,
            $request->label()
        );

        $this->webUi->successMessage(
            "Added new option `{$option->label}` for product `{$product->sku}`"
        );

        return $this->webUi->redirect('products.show', [$product->sku]);
    }

    /**
     * @param int            $productId
     * @param int            $optionId
     * @param PutOptionLabel $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function putLabel(
        int $productId,
        int $optionId,
        PutOptionLabel $request
    ) {
        $option = $this->catalogueRepository->loadOptionById($optionId);

        if (!$option
            || !$option->id
            || (int) $option->product_id !== $productId
        ) {
            return $this->webUi->json(
                ["No such option {$optionId} for product {$productId}."],
                404
            );
        }

        $option->label = $request->label();
        $option->save();

        return $this->webUi->json(compact('option'), 200);
    }

    /**
     * @param int             $optionId
     * @param PutOptionColour $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function putColour(int $optionId, PutOptionColour $request)
    {
        $option = $this->catalogueRepository->loadOptionById($optionId);

        $option->colours()->sync([$request->colourId()]);

        $this->webUi->successMessage(
            "Updated the colour for option `{$option->label}`."
        );

        return $this->redirectToOptionProduct($option);
    }

    /**
     * @param int               $optionId
     * @param ImageOrderRequest $request
     *
     * @return \Illuminate\Http\Response|JsonResponse
     */
    public function putImageOrder(int $optionId, ImageOrderRequest $request)
    {
        $this->catalogueRepository->updateImageOrder(
            $this->catalogueRepository->loadOptionById($optionId),
            $request->imageOrder()
        );

        return $this->webUi->json($request->imageOrder(), 200);
    }

    /**
     * @param int $optionId
     * @param int $imageId
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function detachImage(int $optionId, int $imageId)
    {
        $option = $this->catalogueRepository->loadOptionById($optionId);
        $image = $this->catalogueRepository->loadImageById($imageId);

        $this->catalogueRepository->detachImageFromOwner($image, $option);

        $this->webUi->successMessage(
            "Removed one image from option `{$option->label}`."
        );

        return $this->redirectToOptionProduct($option);
    }

    /**
     * @param ProductOption $option
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    private function redirectToOptionProduct(ProductOption $option)
    {
        return $this->webUi->redirect('products.show', [$option->product->sku]);
    }
}
