<?php

namespace ChingShop\Catalogue\Product;

use OutOfBoundsException;
use ChingShop\Image\Image;
use BadMethodCallException;
use Illuminate\Database\Eloquent\Model;
use ChingShop\Http\View\Customer\Viewable;
use ChingShop\Http\View\Staff\RelaterInterface;
use ChingShop\Http\View\Staff\HttpCrudInterface;
use Illuminate\Database\Eloquent\Relations\Relation;

class ProductPresenter implements HttpCrudInterface, RelaterInterface, Viewable
{
    /** @var Product */
    private $product;

    /** @var array */
    private $relations = [
        Image::class => 'images',
    ];

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
    public function id(): int
    {
        return (int) $this->product->id;
    }

    /**
     * @return string: the routing prefix for this entity
     */
    public function routePrefix(): string
    {
        return 'product::';
    }

    /**
     * @return string
     */
    public function routePath(): string
    {
        return 'staff.products';
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
            'ID'   => $this->id(),
            'slug' => $this->slug(),
        ];
    }

    /**
     * @param Model $related
     * @return Relation
     * @throws OutOfBoundsException
     */
    public function relationTo(Model $related): Relation
    {
        $relationMethod = $this->relationKeyTo($related);
        if (!method_exists($this->product, $relationMethod)) {
            throw new BadMethodCallException(sprintf(
                'No relation method for %s exists on %s',
                get_class($related),
                Product::class
            ));
        }
        return $this->product->{$this->relations[get_class($related)]}();
    }

    /**
     * @param Model $related
     * @return string
     */
    public function relationKeyTo(Model $related): string
    {
        if (empty($this->relations[get_class($related)])) {
            throw new OutOfBoundsException(sprintf(
                'Unknown relation from %s to %s',
                Product::class,
                get_class($related)
            ));
        }
        return $this->relations[get_class($related)];
    }
}
