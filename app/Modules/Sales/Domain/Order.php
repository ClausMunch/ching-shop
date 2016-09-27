<?php

namespace ChingShop\Modules\Sales\Domain;

use ChingShop\Modules\Sales\Domain\Basket\Basket;
use ChingShop\Modules\Sales\Domain\Payment\Payment;
use ChingShop\Modules\User\Model\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Propaganistas\LaravelFakeId\FakeIdTrait;

/**
 * @mixin \Eloquent
 *
 * @property int                    $id
 * @property \Carbon\Carbon         $created_at
 * @property \Carbon\Carbon         $updated_at
 * @property \Carbon\Carbon         $deleted_at
 * @property OrderItem[]|Collection $orderItems
 * @property User                   $user
 * @property Address                $address
 */
class Order extends Model
{
    use SoftDeletes, FakeIdTrait;

    /**
     * @return int
     */
    public function publicId(): int
    {
        return (int) $this->getRouteKey();
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
}
