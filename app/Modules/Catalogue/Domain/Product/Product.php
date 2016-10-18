<?php

namespace ChingShop\Modules\Catalogue\Domain\Product;

use ChingShop\Image\Image;
use ChingShop\Image\ImageOwner;
use ChingShop\Modules\Catalogue\Domain\Price\Price;
use ChingShop\Modules\Catalogue\Domain\Tag\Tag;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
use McCool\LaravelAutoPresenter\HasPresenter;

/**
 * ChingShop\Modules\Catalogue\Domain\Product\Product.
 *
 * @property int                             $id
 * @property string                          $sku
 * @property \Carbon\Carbon                  $created_at
 * @property \Carbon\Carbon                  $updated_at
 *
 * @method static Builder|Product whereId($value)
 * @method static Builder|Product whereSku($value)
 * @method static Builder|Product whereCreatedAt($value)
 * @method static Builder|Product whereUpdatedAt($value)
 *
 * @property string                          $name
 *
 * @method static Builder|Product whereName($value)
 *
 * @property string                          $slug
 * @property string                          $description
 * @property string                          $deleted_at
 *
 * @method static Builder|Product whereSlug($value)
 * @method static Builder|Product whereDescription($value)
 * @method static Builder|Product whereDeletedAt($value)
 * @mixin \Eloquent
 *
 * @property-read Collection|Image[]         $images
 * @property-read Collection|Price[]         $prices
 * @property-read Collection|Tag[]           $tags
 * @property-read Collection|ProductOption[] $options
 *
 * @method Builder inStock()
 */
class Product extends Model implements HasPresenter, ImageOwner
{
    use SoftDeletes, Searchable;

    /** @var array */
    protected $fillable = ['name', 'sku', 'slug', 'description'];

    /** @var BelongsToMany */
    private $imagesRelationship;

    /**
     * @return string
     */
    public function url(): string
    {
        return route('product::view', [$this->id, $this->slug]);
    }

    /**
     * @return Price
     */
    public function price(): Price
    {
        /** @var Price $firstPrice */
        $firstPrice = $this->prices->first();

        return $firstPrice ?? new Price();
    }

    /**
     * @return bool
     */
    public function isStored(): bool
    {
        return (bool) $this->id;
    }

    /**
     * @return BelongsToMany
     */
    public function images(): BelongsToMany
    {
        if ($this->imagesRelationship !== null) {
            return $this->imagesRelationship;
        }

        return $this->belongsToMany(Image::class)
            ->withPivot('position')
            ->orderBy('pivot_position', 'asc');
    }

    /**
     * @return HasMany
     */
    public function prices(): HasMany
    {
        return $this->hasMany(Price::class);
    }

    /**
     * @return HasMany
     */
    public function options(): HasMany
    {
        return $this->hasMany(ProductOption::class);
    }

    /**
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeInStock(Builder $query): Builder
    {
        return $query->whereHas(
            'options',
            function (Builder $query) {
                /* @var ProductOption $query */
                $query->inStock();
            }
        );
    }

    /**
     * @return bool
     */
    public function isInStock(): bool
    {
        return $this->options->contains(
            function ($option) {
                /* @var ProductOption $option */
                return $option->isInStock();
            }
        );
    }

    /**
     * @param int[] $imageIDs
     */
    public function attachImages(array $imageIDs)
    {
        $this->images()->attach($imageIDs);
    }

    /**
     * @param BelongsToMany $relationship
     */
    public function setImagesRelationship(BelongsToMany $relationship)
    {
        $this->imagesRelationship = $relationship;
    }

    /**
     * @param string $sku
     */
    public function setSkuAttribute(string $sku)
    {
        $this->attributes['sku'] = mb_strtoupper($sku);
    }

    /**
     * @param string $slug
     */
    public function setSlugAttribute(string $slug)
    {
        $this->attributes['slug'] = mb_strtolower($slug);
    }

    /**
     * @return BelongsToMany
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    /**
     * Get the presenter class.
     *
     * @return string
     */
    public function getPresenterClass(): string
    {
        return ProductPresenter::class;
    }

    /**
     * @return Collection|Image[]
     */
    public function imageCollection(): Collection
    {
        return $this->images;
    }
}
