<?php

namespace ChingShop\Modules\Catalogue\Model\Price;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder;

/**
 * ChingShop\Modules\Catalogue\Model\Price\Price.
 *
 * @mixin \Eloquent
 *
 * @property int                                                     $id
 * @property int                                                     $units
 * @property int                                                     $subunits
 * @property string                                                  $currency
 * @property \Carbon\Carbon                                          $created_at
 * @property \Carbon\Carbon                                          $updated_at
 * @property string                                                  $deleted_at
 *
 * @method static Builder|Price whereId($value)
 * @method static Builder|Price whereUnits($value)
 * @method static Builder|Price whereSubunits($value)
 * @method static Builder|Price whereCurrency($value)
 * @method static Builder|Price whereCreatedAt($value)
 * @method static Builder|Price whereUpdatedAt($value)
 * @method static Builder|Price whereDeletedAt($value)
 *
 * @property int                                                     $product_id
 * @property-read \ChingShop\Modules\Catalogue\Model\Product\Product $product
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

    /**
     * @return string
     */
    public function formatted(): string
    {
        return '£'.money_format('%i', $this->asFloat());
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
     * @return float
     */
    public function asFloat(): float
    {
        return (float) ($this->units + ($this->subunits / 100));
    }
}
