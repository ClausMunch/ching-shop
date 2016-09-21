<?php

namespace ChingShop\Modules\Sales\Domain\Basket;

use McCool\LaravelAutoPresenter\BasePresenter;

/**
 * Class BasketPresenter.
 */
class BasketPresenter extends BasePresenter
{
    /** @var Basket */
    protected $wrappedObject;

    /**
     * @param Basket $resource
     */
    public function __construct(Basket $resource)
    {
        parent::__construct($resource);

        $this->wrappedObject = $resource;
    }

    /**
     * @return string
     */
    public function totalPrice(): string
    {
        return number_format($this->wrappedObject->totalPrice(), 2);
    }
}
