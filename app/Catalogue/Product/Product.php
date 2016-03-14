<?php

namespace ChingShop\Catalogue\Product;

use ChingShop\Catalogue\Price\Price;
use ChingShop\Image\Image;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * ChingShop\Catalogue\Product\Product.
 *
 * @property int $id
 * @property string $sku
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\ChingShop\Catalogue\Product\Product whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\ChingShop\Catalogue\Product\Product whereSku($value)
 * @method static \Illuminate\Database\Query\Builder|\ChingShop\Catalogue\Product\Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\ChingShop\Catalogue\Product\Product whereUpdatedAt($value)
 * @property string $name
 * @method static \Illuminate\Database\Query\Builder|\ChingShop\Catalogue\Product\Product whereName($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|Image[] $images
 * @property string $slug
 * @property string $description
 * @property string $deleted_at
 * @method static \Illuminate\Database\Query\Builder|\ChingShop\Catalogue\Product\Product whereSlug($value)
 * @method static \Illuminate\Database\Query\Builder|\ChingShop\Catalogue\Product\Product whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\ChingShop\Catalogue\Product\Product whereDeletedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\ChingShop\Catalogue\Price\Price[] $prices
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
        return isset($this->imagesRelationship) ?
            $this->imagesRelationship : $this->belongsToMany(Image::class);
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
