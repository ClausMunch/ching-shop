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
     * @param int $page
     *
     * @return Collection|Order[]
     */
    public function all(int $page = 0)
    {
        return $this->orderResource
            ->orderBy('updated_at', 'desc')
            ->skip($page * 100)
            ->limit(100)
            ->get();
    }
}
