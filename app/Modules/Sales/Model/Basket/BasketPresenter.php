<?php

namespace ChingShop\Modules\Sales\Model\Basket;

use McCool\LaravelAutoPresenter\BasePresenter;

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
}
