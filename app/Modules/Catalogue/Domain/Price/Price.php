<?php

namespace ChingShop\Modules\Catalogue\Domain\Price;

use ChingShop\Modules\Catalogue\Domain\Product\Product;
use ChingShop\Modules\Sales\Domain\Money;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder;

/**
 * The price of something.
 *
 * ChingShop\Modules\Catalogue\Domain\Price\Price.
 *
 * @mixin \Eloquent
 *
 * @property int                                                      $id
 * @property int                                                      $units
 * @property int                                                      $subunits
 * @property string                                                   $currency
 * @property \Carbon\Carbon
 *           $created_at
 * @property \Carbon\Carbon
 *           $updated_at
 * @property string
 *           $deleted_at
 *
 * @method static Builder|Price whereId($value)
 * @method static Builder|Price whereUnits($value)
 * @method static Builder|Price whereSubunits($value)
 * @method static Builder|Price whereCurrency($value)
 * @method static Builder|Price whereCreatedAt($value)
 * @method static Builder|Price whereUpdatedAt($value)
 * @method static Builder|Price whereDeletedAt($value)
 *
 * @property int
 *           $product_id
 * @property-read \ChingShop\Modules\Catalogue\Domain\Product\Product $product
 *
 * @method static Builder|Price whereProductId($value)
 */
class Price extends Model
{
    use SoftDeletes;

    const UNITS_FORMAT = '%d';
    const SUBUNITS_FORMAT = '%02d';

    /** @var array */
    protected $fillable = ['units', 'subunits', 'currency'];

    /** @var string[] */
    protected $touches = ['product'];

    /** @var array */
    protected $casts = [
        'units'    => 'integer',
        'subunits' => 'integer',
    ];

    /**
     * @param int $units
     * @param int $subunits
     *
     * @return Price
     */
    public static function fromSplit(int $units, int $subunits): Price
    {
        return new self(['units' => $units, 'subunits' => $subunits]);
    }

    /**
     * @throws \InvalidArgumentException
     *
     * @return string
     */
    public function formatted(): string
    {
        return $this->asMoney()->formatted();
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
     * @throws \InvalidArgumentException
     *
     * @return Money
     */
    public function asMoney(): Money
    {
        return Money::fromSplit((int) $this->units, (int) $this->subunits);
    }

    /**
     * @throws \InvalidArgumentException
     *
     * @return float
     */
    public function asFloat(): float
    {
        return $this->asMoney()->asFloat();
    }

    /**
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
