<?php

namespace ChingShop\Modules\Sales\Domain\Order;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * Encapsulate behaviours for order persistence and retrieval.
 *
 * Class OrderRepository
 */
class OrderRepository
{
    const DEFAULT_RELATIONS = [
        'address',
        'orderItems.basketItem.productOption.product',
    ];

    /** @var Order|Builder */
    private $orderResource;

    /**
     * @param Order $orderResource
     */
    public function __construct(Order $orderResource)
    {
        $this->orderResource = $orderResource;
    }

    /**
     * @return Collection|Order[]
     */
    public function all()
    {
        return $this->orderResource
            ->with(self::DEFAULT_RELATIONS)
            ->orderBy('updated_at', 'desc')
            ->simplePaginate(100);
    }

    /**
     * @param int $publicId
     *
     * @return Order
     */
    public function byPublicId(int $publicId): Order
    {
        /** @var Order $order */
        $order = $this->orderResource
            ->with(self::DEFAULT_RELATIONS)
            ->where('id', '=', Order::privateId($publicId))
            ->first();

        if ($order instanceof Order) {
            return $order;
        }

        return new Order();
    }
}
