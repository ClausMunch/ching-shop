<?php

namespace ChingShop\Modules\Sales\Domain\Order;

use Carbon\Carbon;
use ChingShop\Domain\PublicId;
use ChingShop\Modules\Sales\Domain\Address;
use ChingShop\Modules\Sales\Domain\Basket\Basket;
use ChingShop\Modules\Sales\Domain\LinePriced;
use ChingShop\Modules\Sales\Domain\Money;
use ChingShop\Modules\Sales\Domain\Offer\OrderOffer;
use ChingShop\Modules\Sales\Domain\Payment\Payment;
use ChingShop\Modules\Shipping\Domain\Dispatch;
use ChingShop\Modules\User\Model\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

/**
 * @mixin \Eloquent
 *
 * @property int                          $id
 * @property \Carbon\Carbon               $created_at
 * @property \Carbon\Carbon               $updated_at
 * @property \Carbon\Carbon               $deleted_at
 * @property-read OrderItem[]|Collection  $orderItems
 * @property-read OrderOffer[]|Collection $orderOffers
 * @property-read Dispatch[]|Collection   $dispatches
 * @property-read User                    $user
 * @property-read Address                 $address
 * @property-read Payment                 $payment
 */
class Order extends Model
{
    use SoftDeletes, PublicId, Notifiable;

    /** @var string */
    public $notifyVia = 'mail';

    /**
     * @param int $publicId
     *
     * @return $this|Model|Builder
     */
    public static function wherePublicId(int $publicId)
    {
        return self::where('id', '=', self::privateId($publicId));
    }

    /**
     * An order contains order items.
     *
     * @return HasMany
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * An order may have order offers.
     *
     * @return HasMany
     */
    public function orderOffers(): HasMany
    {
        return $this->hasMany(OrderOffer::class);
    }

    /**
     * @return HasMany
     */
    public function dispatches(): HasMany
    {
        return $this->hasMany(Dispatch::class, 'order_id', 'id');
    }

    /**
     * An order belongs to a user.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasOne
     */
    public function basket(): HasOne
    {
        return $this->hasOne(Basket::class);
    }

    /**
     * @return HasOne
     */
    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    /**
     * An order has a delivery address.
     *
     * @return BelongsTo
     */
    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    /**
     * @throws \InvalidArgumentException
     *
     * @return Money
     */
    public function totalPrice(): Money
    {
        return (new \Illuminate\Support\Collection())
            ->merge($this->orderItems)
            ->merge($this->orderOffers)
            ->reduce(
                function (Money $total, LinePriced $item) {
                    return $total->add($item->linePrice());
                },
                Money::fromInt(0)
            );
    }

    /**
     * @return string
     */
    public function routeNotificationForMail()
    {
        return $this->payerEmail();
    }

    /**
     * @return string
     */
    public function payerEmail(): string
    {
        if (empty($this->payment->settlement)) {
            return '';
        }

        return $this->payment->settlement->payerEmail();
    }

    /**
     * @return bool
     */
    public function hasBeenDispatched(): bool
    {
        if ($this->relationLoaded('dispatches')) {
            return $this->dispatches->count() > 0;
        }

        return $this->dispatches()->exists();
    }

    /**
     * @return Carbon
     */
    public function dispatchedAt(): Carbon
    {
        if ($this->dispatches->isNotEmpty()) {
            return $this->dispatches->first()->created_at;
        }

        return new Carbon();
    }

    /**
     * @throws \Exception
     *
     * @return bool|null
     */
    public function deAllocate()
    {
        $this->orderItems->each(
            function (OrderItem $item) {
                $item->deAllocate();
            }
        );

        return $this->delete();
    }
}
