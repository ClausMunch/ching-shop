<?php

namespace ChingShop\Modules\Sales\Model\Basket;

use ChingShop\Modules\Sales\Model\Order;
use ChingShop\Modules\User\Model\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use McCool\LaravelAutoPresenter\HasPresenter;

/**
 * @mixin \Eloquent
 *
 * @property int                     $id
 * @property \Carbon\Carbon          $created_at
 * @property \Carbon\Carbon          $updated_at
 * @property \Carbon\Carbon          $deleted_at
 *
 * @property int                     $user_id
 *
 * @property BasketItem[]|Collection $basketItems
 * @property Order                   $order
 * @property User|null               $user
 */
class Basket extends Model implements HasPresenter
{
    /**
     * A basket contains basket items.
     *
     * @return HasMany
     */
    public function basketItems(): HasMany
    {
        return $this->hasMany(BasketItem::class);
    }

    /**
     * A basket points to an order after being purchased.
     *
     * @return BelongsTo
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * A basket may belong to a user.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeActive(Builder $query)
    {
        return $query->whereDoesntHave('order');
    }

    /**
     * Get the presenter class.
     *
     * @return string
     */
    public function getPresenterClass()
    {
        return BasketPresenter::class;
    }

    /**
     * @param int $id
     *
     * @return BasketItem
     */
    public function getItem(int $id): BasketItem
    {
        if ($this->basketItems->contains('id', $id)) {
            return $this->basketItems->where('id', $id)->first();
        }

        return new BasketItem();
    }
}
