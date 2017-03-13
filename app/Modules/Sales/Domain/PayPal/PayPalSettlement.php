<?php

namespace ChingShop\Modules\Sales\Domain\PayPal;

use ChingShop\Modules\Sales\Domain\Payment\Payment;
use ChingShop\Modules\Sales\Domain\Payment\Settlement;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use League\Uri\Schemes\Http;

/**
 * Persistence for a payment settlement from a PayPal checkout.
 *
 * @mixin \Eloquent
 *
 * @property int                   $id
 * @property string                $payment_id
 * @property string                $transaction_id
 * @property string                $payer_id
 * @property string                $payer_email
 * @property \Carbon\Carbon        $created_at
 * @property \Carbon\Carbon        $updated_at
 * @property \Carbon\Carbon        $deleted_at
 * @property-read PayPalInitiation $initiation
 */
class PayPalSettlement extends Model implements Settlement
{
    use SoftDeletes;

    /** @var string[] */
    protected $fillable = [
        'payment_id',
        'transaction_id',
        'payer_id',
        'payer_email',
    ];

    /** @var string */
    protected $table = 'paypal_settlements';

    /**
     * @return MorphOne
     */
    public function payment(): MorphOne
    {
        return $this->morphOne(Payment::class, 'settlement');
    }

    /**
     * @return HasOne
     */
    public function initiation(): HasOne
    {
        return $this->hasOne(
            PayPalInitiation::class,
            'payment_id',
            'payment_id'
        );
    }

    /**
     * @return string
     */
    public function type(): string
    {
        return 'paypal';
    }

    /**
     * @return string
     */
    public function id(): string
    {
        if (isset($this->transaction_id)) {
            return $this->transaction_id;
        }

        return (string) $this->payment_id;
    }

    /**
     * @return Http
     */
    public function url(): Http
    {
        $base = Http::createFromString(config('payment.paypal.base-url'));

        if (empty($this->transaction_id)) {
            return $base;
        }

        return $base->withPath("/activity/payment/{$this->transaction_id}");
    }

    /**
     * @return string
     */
    public function payerEmail(): string
    {
        return (string) $this->payer_email;
    }
}
