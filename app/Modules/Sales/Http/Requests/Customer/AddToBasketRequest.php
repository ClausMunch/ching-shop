<?php

namespace ChingShop\Modules\Sales\Http\Requests\Customer;

use ChingShop\Http\Requests\Request;
use Illuminate\Http\Request as HttpRequest;

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

    /**
     * @param HttpRequest $request
     *
     * @return bool
     */
    public function authorize(HttpRequest $request): bool
    {
        // Anyone can add to their basket.
        return true;
    }
}
