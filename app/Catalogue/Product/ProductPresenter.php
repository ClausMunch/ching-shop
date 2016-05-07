<?php

namespace ChingShop\Catalogue\Product;

use ChingShop\Http\View\Customer\Viewable;
use ChingShop\Http\View\Staff\HttpCrudInterface;
use ChingShop\Http\View\Staff\RelaterInterface;
use ChingShop\Image\Image;
use McCool\LaravelAutoPresenter\BasePresenter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use OutOfBoundsException;

class ProductPresenter extends BasePresenter implements
    HttpCrudInterface,
    RelaterInterface,
    Viewable
{
    /** @var Product */
    protected $wrappedObject;

    /** @var array */
    private $relations = [
        Image::class => 'images',
    ];

    /**
     * @param Product $resource
     */
    public function __construct(Product $resource)
    {
        parent::__construct($resource);
        $this->wrappedObject = $resource;
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return str_limit((string) $this->wrappedObject->name, 100);
    }

    /**
     * @return string
     */
    public function sku(): string
    {
        return (string) $this->wrappedObject->sku;
    }

    /**
     * @return bool
     */
    public function isStored(): bool
    {
        return $this->wrappedObject->isStored();
    }

    /**
     * @return int
     */
    public function id(): int
    {
        return (int) $this->wrappedObject->id;
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
    public function crudId(): string
    {
        return $this->sku();
    }

    /**
     * @return string
     */
    public function slug(): string
    {
        return (string) $this->wrappedObject->slug;
    }

    /**
     * @return string
     */
    public function description(): string
    {
        return (string) $this->wrappedObject->description;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|Image[]
     */
    public function images()
    {
        return $this->wrappedObject->images;
    }

    /**
     * @return Image|null
     */
    public function mainImage()
    {
        $firstImage = $this->wrappedObject->images->first();

        return $firstImage ? $firstImage : new Image();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|Image[]
     */
    public function otherImages()
    {
        return $this->wrappedObject->images->slice(1);
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
     *
     * @throws OutOfBoundsException
     *
     * @return Relation
     */
    public function relationTo(Model $related): Relation
    {
        return $this->wrappedObject->{$this->relationKeyTo($related)}();
    }

    /**
     * @param Model $related
     *
     * @return string
     */
    public function relationKeyTo(Model $related): string
    {
        return $this->relations[get_class($related)];
    }

    /**
     * @return string
     */
    public function price(): string
    {
        $firstPrice = $this->wrappedObject->prices->first();

        return $firstPrice ? $firstPrice->formatted() : '';
    }

    /**
     * @return int
     */
    public function priceUnits(): int
    {
        $firstPrice = $this->wrappedObject->prices->first();

        return $firstPrice ? $firstPrice->units : 0;
    }

    /**
     * @return int
     */
    public function priceSubUnits(): int
    {
        $firstPrice = $this->wrappedObject->prices->first();

        return $firstPrice ? $firstPrice->subUnitsFormatted() : 0;
    }
}
