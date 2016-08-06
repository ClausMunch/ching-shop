<?php

namespace ChingShop\Modules\Sales\Http\Requests\Customer;

use ChingShop\Http\Requests\Request;
use Illuminate\Http\Request as HttpRequest;

class RemoveFromBasketRequest extends Request
{
    const BASKET_ITEM_ID = 'basket-item-id';

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

    /**
     * @param HttpRequest $request
     *
     * @return bool
     */
    public function authorize(HttpRequest $request): bool
    {
        return true;
    }
}
