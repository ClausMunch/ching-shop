<?php

namespace ChingShop\Catalogue\Product;

use ChingShop\Http\View\HttpCrud;

class ProductPresenter implements HttpCrud
{
    /** @var Product */
    private $product;

    /**
     * @param Product $product
     */
    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return str_limit((string) $this->product->name, 100);
    }

    /**
     * @return string
     */
    public function SKU(): string
    {
        return (string) $this->product->sku;
    }

    /**
     * @return bool
     */
    public function isStored(): bool
    {
        return $this->product->isStored();
    }

    /**
     * @return int
     */
    public function ID(): int
    {
        return (int) $this->product->id;
    }

    /**
     * @return string
     */
    public function crudRoutePrefix(): string
    {
        return 'staff.products.';
    }

    /**
     * @return string
     */
    public function crudID(): string
    {
        return $this->SKU();
    }
}
