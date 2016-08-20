<?php

namespace ChingShop\Modules\Sales\Http\Requests\Customer;

use ChingShop\Http\Requests\Request;

class AddToBasketRequest extends Request
{
    /**
     * @return int
     */
    public function optionId(): int
    {
        return (int) $this->get('product-option');
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'product-option' => 'required|integer|exists:product_options,id',
        ];
    }
}
