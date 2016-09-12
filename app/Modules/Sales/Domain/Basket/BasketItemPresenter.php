<?php

namespace ChingShop\Modules\Sales\Domain\Basket;

use McCool\LaravelAutoPresenter\BasePresenter;

class BasketItemPresenter extends BasePresenter
{
    /** @var BasketItem */
    protected $wrappedObject;

    /**
     * @param BasketItem $resource
     */
    public function __construct(BasketItem $resource)
    {
        parent::__construct($resource);

        $this->wrappedObject = $resource;
    }
}
