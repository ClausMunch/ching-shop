<?php

namespace ChingShop\Modules\Sales\Http\Controllers\Customer;

use ChingShop\Http\Controllers\Controller;
use ChingShop\Http\WebUi;
use ChingShop\Modules\Catalogue\Model\Product\ProductOptionRepository;
use ChingShop\Modules\Sales\Http\Requests\Customer\AddToBasketRequest;
use ChingShop\Modules\Sales\Http\Requests\Customer\RemoveFromBasketRequest;
use ChingShop\Modules\Sales\Model\Clerk;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Class BasketController.
 */
class BasketController extends Controller
{
    /** @var Clerk */
    private $clerk;

    /** @var ProductOptionRepository */
    private $optionRepository;

    /** @var WebUi */
    private $webUi;

    /**
     * BasketController constructor.
     *
     * @param Clerk                   $clerk
     * @param ProductOptionRepository $optionRepository
     * @param WebUi                   $webUi
     */
    public function __construct(
        Clerk $clerk,
        ProductOptionRepository $optionRepository,
        WebUi $webUi
    ) {
        $this->clerk = $clerk;
        $this->optionRepository = $optionRepository;
        $this->webUi = $webUi;
    }

    /**
     * @param AddToBasketRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addProductOptionAction(AddToBasketRequest $request)
    {
        $productOption = $this->optionRepository->loadById(
            $request->optionId()
        );
        $this->clerk->addProductOptionToBasket($productOption);

        $this->webUi->successMessage(
            sprintf(
                '1 &#215; <strong>%s (%s)</strong> was added to your basket.',
                $productOption->product->name,
                $productOption->label
            )
        );

        return $this->webUi->redirect('sales.customer.basket');
    }

    /**
     * View the shopping basket.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function viewBasketAction()
    {
        return $this->webUi->view('customer.basket.view');
    }

    /**
     * @param RemoveFromBasketRequest $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     * @throws \Exception
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removeBasketItemAction(RemoveFromBasketRequest $request)
    {
        if (!$this->clerk->basket()->getItem($request->basketItemId())->id) {
            throw new BadRequestHttpException(
                sprintf(
                    'Basket does not contain any item with id `%s`.',
                    $request->basketItemId()
                )
            );
        }

        /** @var $item */
        $item = $this->clerk->basket()->getItem($request->basketItemId());
        $item->delete();

        $this->webUi->successMessage(
            sprintf(
                '1 &#215; <strong>%s (%s)</strong> %s',
                $item->productOption->product->name,
                $item->productOption->label,
                ' was removed from your basket.'
            )
        );

        return $this->webUi->redirect('sales.customer.basket');
    }
}
