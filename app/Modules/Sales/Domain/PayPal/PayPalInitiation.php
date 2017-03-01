<?php

namespace ChingShop\Modules\Sales\Domain\PayPal;

use ChingShop\Modules\Sales\Domain\Basket\Basket;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Persistence for initiation data of a PayPal payment, before the transaction
 * has been executed.
 *
 * @mixin \Eloquent
 *
 * @property int                   $id
 * @property \Carbon\Carbon        $created_at
 * @property \Carbon\Carbon        $updated_at
 * @property \Carbon\Carbon        $deleted_at
 * @property string                $payment_id
 * @property float                 $amount
 * @property Basket|null           $basket
 *
 * @property-read PayPalSettlement $settlement
 */
class PayPalInitiation extends Model
{
    use SoftDeletes;

    /** @var string */
    protected $table = 'paypal_initiations';

    /** @var array */
    protected $fillable = ['payment_id', 'amount'];

    /**
     * @return BelongsTo
     */
    public function basket(): BelongsTo
    {
        return $this->belongsTo(Basket::class);
    }

    /**
     * @param string $value
     *
     * @return float
     */
    public function getAmountAttribute($value): float
    {
        return (float) $value;
    }

    /**
     * @return HasOne
     */
    public function settlement(): HasOne
    {
        return $this->hasOne(
            PayPalSettlement::class,
            'payment_id',
            'payment_id'
        );
    }
}
