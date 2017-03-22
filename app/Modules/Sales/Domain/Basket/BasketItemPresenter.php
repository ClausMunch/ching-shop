<?php

namespace ChingShop\Modules\Sales\Domain\Basket;

use ChingShop\Modules\Catalogue\Domain\Product\Product;
use ChingShop\Modules\Sales\Domain\LinePriced;
use ChingShop\Modules\Sales\Domain\Money;
use ChingShop\Modules\Sales\Domain\Offer\OfferComponent;
use McCool\LaravelAutoPresenter\BasePresenter;

/**
 * Class BasketItemPresenter.
 */
class BasketItemPresenter extends BasePresenter implements
    OfferComponent,
    LinePriced
{
    /** @var BasketItem */
    protected $wrappedObject;

    /**
     * @param BasketItem $resource
     */
    public function __construct(BasketItem $resource)
    {
        $this->wrappedObject = $resource;
    }

    /**
     * @return int
     */
    public function id(): int
    {
        return $this->wrappedObject->id();
    }

    /**
     * @return Product
     */
    public function product(): Product
    {
        return $this->wrappedObject->product();
    }

    /**
     * @throws \InvalidArgumentException
     *
     * @return Money
     */
    public function linePrice(): Money
    {
        return $this->wrappedObject->linePrice();
    }
}
