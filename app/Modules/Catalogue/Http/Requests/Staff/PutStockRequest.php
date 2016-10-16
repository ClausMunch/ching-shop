<?php

namespace ChingShop\Modules\Catalogue\Http\Requests\Staff;

use ChingShop\Http\Requests\Staff\StaffRequest;

/**
 * Request to update the stock quantity of a product option.
 */
class PutStockRequest extends StaffRequest
{
    /**
     * @return int
     */
    public function quantity(): int
    {
        return (int) abs($this->get('quantity'));
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'quantity' => 'required|integer|min:0|max:999',
        ];
    }
}
