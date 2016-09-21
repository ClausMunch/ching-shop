<?php

namespace ChingShop\Modules\Sales\Http\Requests\Customer;

use ChingShop\Http\Requests\Request;

/**
 * Class RemoveFromBasketRequest.
 */
class RemoveFromBasketRequest extends Request
{
    const BASKET_ITEM_ID = 'basket-item-id';

    /**
     * @return int
     */
    public function basketItemId(): int
    {
        return (int) $this->get(self::BASKET_ITEM_ID);
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            self::BASKET_ITEM_ID => 'required|integer|exists:basket_items,id',
        ];
    }
}
