<?php

namespace ChingShop\Catalogue\Product;

use Illuminate\Database\Eloquent\Model;

/**
 * ChingShop\Catalogue\Product\Product
 *
 * @property integer $id
 * @property string $sku
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\ChingShop\Catalogue\Product\Product whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\ChingShop\Catalogue\Product\Product whereSku($value)
 * @method static \Illuminate\Database\Query\Builder|\ChingShop\Catalogue\Product\Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\ChingShop\Catalogue\Product\Product whereUpdatedAt($value)
 * @property string $name
 * @method static \Illuminate\Database\Query\Builder|\ChingShop\Catalogue\Product\Product whereName($value)
 */
class Product extends Model
{
    /** @var array */
    protected $fillable = ['name', 'sku'];

    /** @var array */
    protected $guarded = ['id'];

    /**
     * @return bool
     */
    public function isStored(): bool
    {
        return (bool) $this->id;
    }
}
