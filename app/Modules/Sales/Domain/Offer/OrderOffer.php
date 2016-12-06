<?php

namespace ChingShop\Modules\Sales\Domain\Offer;

use ChingShop\Modules\Sales\Domain\LinePriced;
use ChingShop\Modules\Sales\Domain\Money;
use ChingShop\Modules\Sales\Domain\Order\Order;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Record of an offer applied to an order.
 *
 * @property int             $id
 * @property \Carbon\Carbon  $created_at
 * @property \Carbon\Carbon  $updated_at
 * @property \Carbon\Carbon  $deleted_at
 *
 * @property string          $offer_name
 * @property Money           $amount
 * @property Money           $original_price
 *
 * @property-read Order      $order
 * @property-read Offer|null $offer
 */
class OrderOffer extends Model implements LinePriced
{
    use SoftDeletes;

    /** @var string[] */
    protected $fillable = [
        'order_id',
        'offer_id',
        'offer_name',
        'amount',
        'original_price',
    ];

    /**
     * @param Order          $order
     * @param PotentialOffer $potentialOffer
     *
     * @return OrderOffer
     * @throws \InvalidArgumentException
     */
    public static function makeFrom(
        Order $order,
        PotentialOffer $potentialOffer
    ): OrderOffer {
        return new OrderOffer(
            [
                'order_id'       => $order->id,
                'offer_id'       => $potentialOffer->offer()->id,
                'offer_name'     => (string) $potentialOffer->offer()->name,
                'amount'         => $potentialOffer->linePrice()->amount(),
                'original_price' => $potentialOffer->originalPrice()->amount(),
            ]
        );
    }

    /**
     * @return Money
     * @throws \InvalidArgumentException
     */
    public function getAmountAttribute()
    {
        return Money::fromInt($this->attributes['amount']);
    }

    /**
     * @return Money
     * @throws \InvalidArgumentException
     */
    public function getOriginalPriceAttribute()
    {
        return Money::fromInt($this->attributes['original_price']);
    }

    /**
     * @return Money
     * @throws \InvalidArgumentException
     */
    public function linePrice(): Money
    {
        return Money::fromInt((int) $this->amount->amount());
    }

    /**
     * @return BelongsTo
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * @return BelongsTo
     */
    public function offer(): BelongsTo
    {
        return $this->belongsTo(Offer::class);
    }
}
