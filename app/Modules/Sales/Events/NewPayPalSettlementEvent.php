<?php

namespace ChingShop\Modules\Sales\Events;

use ChingShop\Events\Event;
use ChingShop\Modules\Sales\Domain\PayPal\PayPalSettlement;
use Illuminate\Queue\SerializesModels;

/**
 * Class NewPayPalSettlementEvent.
 */
class NewPayPalSettlementEvent extends Event
{
    use SerializesModels;

    /** @var PayPalSettlement */
    public $settlement;

    /**
     * @param PayPalSettlement $settlement
     */
    public function __construct(PayPalSettlement $settlement)
    {
        $this->settlement = $settlement;
    }
}
