<?php

namespace ChingShop\Modules\Catalogue\Http\Controllers\Staff;

use ChingShop\Http\Controllers\Controller;
use ChingShop\Http\WebUi;
use ChingShop\Modules\Catalogue\Domain\Inventory\StockItem;
use ChingShop\Modules\Catalogue\Domain\Product\Product;
use ChingShop\Modules\Catalogue\Domain\Product\ProductOption;
use ChingShop\Modules\Catalogue\Http\Requests\Staff\PutStockRequest;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Actions for stock management.
 */
class StockController extends Controller
{
    /** @var ProductOption */
    private $optionResource;

    /** @var WebUi */
    private $webUi;

    /**
     * @param ProductOption $optionResource
     * @param WebUi         $webUi
     */
    public function __construct(ProductOption $optionResource, WebUi $webUi)
    {
        $this->optionResource = $optionResource;
        $this->webUi = $webUi;
    }

    /**
     * @param int             $optionId
     * @param PutStockRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     */
    public function putStock(int $optionId, PutStockRequest $request)
    {
        $option = $this->optionResource
            ->with(['product', 'availableStock'])
            ->where('id', '=', $optionId)
            ->firstOrFail();

        if ($request->quantity() === count($option->availableStock)) {
            $this->webUi->infoMessage(
                "Stock for `{$option->label}` was not changed."
            );

            return $this->redirectToShowProduct($option->product);
        }

        if ($request->quantity() > count($option->availableStock)) {
            $new = $this->increaseStock($request->quantity(), $option);
            $this->webUi->successMessage(
                "Added {$new} stock item/s for `{$option->label}`."
            );

            return $this->redirectToShowProduct($option->product);
        }

        if ($request->quantity() < count($option->availableStock)) {
            $removed = $this->reduceStock($request->quantity(), $option);
            $this->webUi->successMessage(
                "Removed {$removed} stock item/s from `{$option->label}`."
            );

            return $this->redirectToShowProduct($option->product);
        }

        throw new BadRequestHttpException('Failed to diff stock quantity.');
    }

    /**
     * @param int           $newQuantity
     * @param ProductOption $option
     *
     * @return int
     */
    private function increaseStock(int $newQuantity, ProductOption $option): int
    {
        /** @var StockItem[] $newStock */
        $newStock = [];
        $add = $newQuantity - count($option->availableStock);
        for ($i = 0; $i < $add; $i++) {
            $newStock[] = new StockItem();
        }

        $option->stockItems()->saveMany($newStock);

        return count($newStock);
    }

    /**
     * @param int           $newQuantity
     * @param ProductOption $option
     *
     * @return int
     */
    private function reduceStock(int $newQuantity, ProductOption $option): int
    {
        $deleted = 0;
        while ($option->availableStock->count() > $newQuantity) {
            $option->availableStock->pop()->delete();
            $deleted++;
        }

        return $deleted;
    }

    /**
     * @param Product $product
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    private function redirectToShowProduct(Product $product)
    {
        return $this->webUi->redirect('products.show', [$product->sku]);
    }
}
