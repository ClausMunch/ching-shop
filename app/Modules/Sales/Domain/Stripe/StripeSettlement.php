<?php

namespace ChingShop\Modules\Sales\Domain\Stripe;

use ChingShop\Modules\Sales\Domain\Payment\Payment;
use ChingShop\Modules\Sales\Domain\Payment\Settlement;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use League\Uri\Schemes\Http;
use Stripe\Charge;

/**
 * A payment settlement made through Stripe.
 *
 * @mixin \Eloquent
 *
 * @property int            $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 * @property string         $stripe_id
 * @property int            $amount
 * @property string         $balance_transaction
 * @property bool           $captured
 * @property int            $created
 * @property string         $currency
 * @property string         $description
 * @property string         $failure_code
 * @property string         $failure_message
 * @property bool           $paid
 * @property string         $status
 * @property string         $address_zip
 * @property string         $name
 */
class StripeSettlement extends Model implements Settlement
{
    use SoftDeletes;

    /** @var string[] */
    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'amount'   => 'integer',
        'captured' => 'boolean',
        'paid'     => 'boolean',
        'created'  => 'timestamp',
    ];

    /**
     * @return string
     */
    public function type(): string
    {
        return 'stripe';
    }

    /**
     * @return string
     */
    public function id(): string
    {
        return (string) $this->stripe_id;
    }

    /**
     * @param Charge $charge
     *
     * @return StripeSettlement|self
     */
    public function fillFromCharge(Charge $charge): StripeSettlement
    {
        $this->stripe_id = $charge->id;
        $this->amount = $charge->amount;
        $this->balance_transaction = $charge->balance_transaction;
        $this->captured = $charge->captured;
        $this->created = $charge->created;
        $this->currency = $charge->currency;
        $this->description = $charge->description;
        $this->failure_code = $charge->failure_code;
        $this->failure_message = $charge->failure_message;
        $this->paid = $charge->paid;
        $this->status = $charge->status;

        if (isset($charge->source->address_zip)) {
            $this->address_zip = $charge->source->address_zip;
        }

        if (isset($charge->source->name)) {
            $this->name = $charge->source->name;
        }

        return $this;
    }

    /**
     * @return MorphOne
     */
    public function payment(): MorphOne
    {
        return $this->morphOne(Payment::class, 'settlement');
    }

    /**
     * Get a URL for information about this settlement.
     *
     * @return Http
     */
    public function url(): Http
    {
        return Http::createFromString(
            "https://dashboard.stripe.com/payments/{$this->stripe_id}"
        );
    }
}
