<?php

namespace ChingShop\Catalogue\Price;

use ChingShop\Catalogue\Product\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * ChingShop\Catalogue\Price\Price
 *
 * @mixin \Eloquent
 * @property integer $id
 * @property integer $units
 * @property integer $subunits
 * @property string $currency
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * @method static \Illuminate\Database\Query\Builder|\ChingShop\Catalogue\Price\Price whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\ChingShop\Catalogue\Price\Price whereUnits($value)
 * @method static \Illuminate\Database\Query\Builder|\ChingShop\Catalogue\Price\Price whereSubunits($value)
 * @method static \Illuminate\Database\Query\Builder|\ChingShop\Catalogue\Price\Price whereCurrency($value)
 * @method static \Illuminate\Database\Query\Builder|\ChingShop\Catalogue\Price\Price whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\ChingShop\Catalogue\Price\Price whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\ChingShop\Catalogue\Price\Price whereDeletedAt($value)
 * @property integer $product_id
 * @property-read \ChingShop\Catalogue\Product\Product $product
 * @method static \Illuminate\Database\Query\Builder|\ChingShop\Catalogue\Price\Price whereProductId($value)
 */
class Price extends Model
{
    use SoftDeletes;

    const UNITS_FORMAT = '%d';
    const SUBUNITS_FORMAT = '%02d';

    /** @var array */
    protected $fillable = ['units', 'subunits', 'currency'];

    /** @var array */
    protected $guarded = ['id'];

    /**
     * @return string
     */
    public function formatted(): string
    {
        return sprintf(
            '£'.self::UNITS_FORMAT.'.'.self::SUBUNITS_FORMAT,
            $this->units,
            $this->subunits
        );
    }

    /**
     * @return string
     */
    public function subUnitsFormatted(): string
    {
        return sprintf(
            self::SUBUNITS_FORMAT,
            $this->subunits
        );
    }

    /**
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
