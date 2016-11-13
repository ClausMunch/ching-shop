<?php

namespace ChingShop\Modules\Sales\Domain;

use ChingShop\Modules\Catalogue\Domain\Product\ProductOption;
use ChingShop\Modules\Sales\Domain\Basket\Basket;
use ChingShop\Modules\Sales\Domain\Basket\BasketItem;
use ChingShop\Modules\Sales\Domain\Payment\StockAllocationException;
use ChingShop\Modules\User\Model\User;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Session\Store;

/**
 * Class Clerk.
 */
class Clerk
{
    const SESSION_BASKET = 'basket_id';

    /** @var Store */
    private $session;

    /** @var Guard */
    private $guard;

    /** @var Basket */
    private $basketResource;

    /** @var Basket */
    private $basket;

    /**
     * @param Store  $session
     * @param Guard  $guard
     * @param Basket $basketResource
     */
    public function __construct(
        Store $session,
        Guard $guard,
        Basket $basketResource
    ) {
        $this->session = $session;
        $this->guard = $guard;
        $this->basketResource = $basketResource;
    }

    /**
     * Get the current user's basket or create one for them.
     *
     * @return Basket
     */
    public function basket(): Basket
    {
        $this->basket = $this->getBasket();

        // Update non-empty baskets.
        if ($this->basket->basketItems->count() > 0) {
            $this->saveBasket();
        }

        return $this->basket;
    }

    /**
     * @param ProductOption $productOption
     *
     * @throws \ChingShop\Modules\Sales\Domain\Payment\StockAllocationException
     *
     * @return bool
     */
    public function addProductOptionToBasket(ProductOption $productOption)
    {
        // Ensure basket saved before associating.
        $this->basket = $this->getBasket();
        $this->saveBasket();

        // Check stock.
        $this->checkStock($productOption);

        // Add to basket.
        $basketItem = new BasketItem();
        $basketItem->productOption()->associate($productOption);

        $saved = (bool) $this->basket->basketItems()->save($basketItem);

        $this->basket()->basketItems->add($basketItem);

        return $saved;
    }

    /**
     * @param int $basketItemId
     *
     * @throws \Exception
     *
     * @return BasketItem
     */
    public function removeBasketItem(int $basketItemId)
    {
        $item = $this->basket()->getItem($basketItemId);
        $item->delete();

        // Unset basket to ensure reload.
        $this->basket = null;

        return $item;
    }

    /**
     * Ensure the basket is stored with current user_id and noted in the
     * session.
     */
    public function saveBasket()
    {
        if ($this->guard->user() instanceof User
            && $this->guard->user()->getAuthIdentifier()
        ) {
            $this->basket->user_id = $this->guard->user()->getAuthIdentifier();
        }
        if ($this->basket->exists()) {
            $this->basket->save();
        }
        $this->session->set(self::SESSION_BASKET, $this->basket->id);
    }

    /**
     * @return Basket
     */
    private function getBasket(): Basket
    {
        if ($this->basket !== null) {
            return $this->basket;
        }

        $this->basket = $this->basketResource
            ->with('basketItems.productOption.product.prices')
            ->where(
                'id',
                '=',
                $this->session->get(self::SESSION_BASKET)
            )
            ->first();

        if ($this->basket instanceof Basket) {
            return $this->basket;
        }

        if (!$this->guard->user() instanceof User
            || !$this->guard->user()->getAuthIdentifier()
        ) {
            return new Basket();
        }

        $this->basket = $this->basketResource
            ->with('basketItems.productOption.product.prices')
            ->where(
                'user_id',
                '=',
                $this->guard->user()->getAuthIdentifier()
            )
            ->first();

        if ($this->basket instanceof Basket) {
            return $this->basket;
        }

        return new Basket();
    }

    /**
     * @param ProductOption $productOption
     *
     * @throws \ChingShop\Modules\Sales\Domain\Payment\StockAllocationException
     */
    private function checkStock(ProductOption $productOption)
    {
        $inBasketCount = $this->basket->itemsForOption($productOption)->count();
        $availableCount = $productOption->availableStock()->count();
        if ($inBasketCount + 1 > $availableCount) {
            throw new StockAllocationException(
                sprintf(
                    'Only %d %s are available, and there %s.',
                    $availableCount,
                    $productOption->name(),
                    "are already {$inBasketCount} in the basket"
                )
            );
        }
    }
}
