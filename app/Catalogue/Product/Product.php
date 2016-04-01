<?php

namespace ChingShop\Catalogue\Product;

use ChingShop\Catalogue\Price\Price;
use ChingShop\Image\Image;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder;

/**
 * ChingShop\Catalogue\Product\Product.
 *
 * @property int $id
 * @property string $sku
 * @property \Carbon\Carbon $created_atProductRepository
 * @property \Carbon\Carbon $updated_at
 *
 * @method static Builder|Product whereId($value)
 * @method static Builder|Product whereSku($value)
 * @method static Builder|Product whereCreatedAt($value)
 * @method static Builder|Product whereUpdatedAt($value)
 *
 * @property string $name
 *
 * @method static Builder|Product whereName($value)
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|Image[] $images
 * @property string $slug
 * @property string $description
 * @property string $deleted_at
 *
 * @method static Builder|Product whereSlug($value)
 * @method static Builder|Product whereDescription($value)
 * @method static Builder|Product whereDeletedAt($value)
 * @mixin \Eloquent
 *
 * @property-read Collection|\ChingShop\Catalogue\Price\Price[] $prices
 */
class Product extends Model
{
    use SoftDeletes;

    /** @var array */
    protected $fillable = ['name', 'sku', 'slug', 'description'];

    /** @var array */
    protected $guarded = ['id'];

    /** @var BelongsToMany */
    private $imagesRelationship;

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
        if (isset($this->imagesRelationship)) {
            return $this->imagesRelationship;
        }

        return $this->belongsToMany(Image::class);
    }

    /**
     * @return HasMany
     */
    public function prices(): HasMany
    {
        return $this->hasMany(Price::class);
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
}
