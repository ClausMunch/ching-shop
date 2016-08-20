<?php

namespace ChingShop\Http\Requests\Staff\Catalogue\Product;

use ChingShop\Http\Requests\Staff\StaffRequest;

/**
 * Class SetPriceRequest.
 */
class SetPriceRequest extends StaffRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'units'    => 'required|integer|min:0|max:999',
            'subunits' => 'required|integer|min:0|max:99',
        ];
    }
}
