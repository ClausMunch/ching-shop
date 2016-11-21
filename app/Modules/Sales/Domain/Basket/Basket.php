<?php

namespace ChingShop\Modules\Sales\Domain\Basket;

use ChingShop\Modules\Catalogue\Domain\Product\ProductOption;
use ChingShop\Modules\Sales\Domain\Address;
use ChingShop\Modules\Sales\Domain\Money;
use ChingShop\Modules\Sales\Domain\Order\Order;
use ChingShop\Modules\User\Model\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use McCool\LaravelAutoPresenter\HasPresenter;

/**
 * @mixin \Eloquent
 *
 * @property int                     $id
 * @property \Carbon\Carbon          $created_at
 * @property \Carbon\Carbon          $updated_at
 * @property \Carbon\Carbon          $deleted_at
 * @property int                     $user_id
 * @property BasketItem[]|Collection $basketItems
 * @property Order                   $order
 * @property User|null               $user
 * @property Address|null            $address
 */
class Basket extends Model implements HasPresenter
{
    use SoftDeletes;

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
     * @param ProductOption $option
     *
     * @return Collection|BasketItem[]
     */
    public function itemsForOption(ProductOption $option): Collection
    {
        return $this->basketItems->filter(
            function ($item) use ($option) {
                /* @var BasketItem $item */
                return $item->productOption->id === $option->id;
            }
        );
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
     * A basket may have an address (during checkout).
     *
     * @return BelongsTo
     */
    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
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
     * @return BasketItem|BasketItemPresenter
     */
    public function getItem(int $id)
    {
        if ($this->basketItems->contains('id', $id)) {
            return $this->basketItems->where('id', $id)->first();
        }

        return new BasketItem();
    }

    /**
     * @throws \InvalidArgumentException
     *
     * @return Money
     */
    public function totalPrice(): Money
    {
        return Money::fromDecimal(
            array_reduce(
                $this->basketItems->all(),
                function (float $total, $item) {
                    /* @var BasketItem $item */
                    return $total + $item->priceAsFloat();
                },
                0.0
            )
        );
    }

    /**
     * @throws \InvalidArgumentException
     *
     * @return float
     */
    public function subUnitPrice(): float
    {
        return $this->totalPrice()->asFloat() * 100;
    }
}
