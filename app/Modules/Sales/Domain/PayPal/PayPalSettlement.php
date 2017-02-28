<?php

namespace ChingShop\Modules\Sales\Domain\PayPal;

use ChingShop\Modules\Sales\Domain\Payment\Payment;
use ChingShop\Modules\Sales\Domain\Payment\Settlement;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use League\Uri\Schemes\Http;

/**
 * Persistence for a payment settlement from a PayPal checkout.
 *
 * @mixin \Eloquent
 *
 * @property int            $id
 * @property string         $payment_id
 * @property string         $payer_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 */
class PayPalSettlement extends Model implements Settlement
{
    use SoftDeletes;

    /** @var string[] */
    protected $fillable = ['payment_id', 'payer_id'];

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
        return (string) $this->payment_id;
    }

    /**
     * @return Http
     */
    public function url(): Http
    {
        return Http::createFromString(
            config('payment.paypal.base-url')
        )->withPath("/activity/payment/{$this->payment_id}");
    }
}
