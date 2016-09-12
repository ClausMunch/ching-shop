<?php

namespace ChingShop\Modules\Catalogue\Domain\Product;

use ChingShop\Http\View\Staff\HttpCrudInterface;
use McCool\LaravelAutoPresenter\BasePresenter;

/**
 * Class ProductOptionPresenter.
 */
class ProductOptionPresenter extends BasePresenter implements HttpCrudInterface
{
    /** @var ProductOption */
    protected $wrappedObject;

    /**
     * @param ProductOption $resource
     */
    public function __construct(ProductOption $resource)
    {
        parent::__construct($resource);
        $this->wrappedObject = $resource;
    }

    /**
     * Whether this resource has already been persisted.
     *
     * @return bool
     */
    public function isStored(): bool
    {
        return (bool) $this->wrappedObject->id;
    }

    /**
     * Routing name prefix for persisting this resource.
     *
     * @return string
     */
    public function routePath(): string
    {
        return 'catalogue.staff.products.options';
    }

    /**
     * Identifier used when persisting this resource.
     *
     * @return string
     */
    public function crudId(): string
    {
        return $this->wrappedObject->id;
    }
}
