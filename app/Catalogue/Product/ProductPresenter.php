<?php

namespace ChingShop\Catalogue\Product;

use ChingShop\Http\View\Customer\Viewable;
use ChingShop\Http\View\Staff\HttpCrud;
use ChingShop\Image\Image;

class ProductPresenter implements HttpCrud, Viewable
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

    /**
     * @return string
     */
    public function slug(): string
    {
        return (string) $this->product->slug;
    }

    /**
     * @return string
     */
    public function description(): string
    {
        return (string) $this->product->description;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|Image[]
     */
    public function images()
    {
        return $this->product->images;
    }

    /**
     * @return Image|null
     */
    public function mainImage()
    {
        return $this->product->images->first();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|Image[]
     */
    public function otherImages()
    {
        return $this->product->images->slice(1);
    }

    /**
     * @return array of location key => value
     */
    public function locationParts(): array
    {
        return [
            'ID'   => $this->ID(),
            'slug' => $this->slug(),
        ];
    }

    /**
     * @return string: the routing prefix for this entity
     */
    public function routePrefix(): string
    {
        return 'product::';
    }
}
