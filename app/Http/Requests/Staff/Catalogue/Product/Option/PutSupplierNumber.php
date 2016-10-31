<?php

namespace ChingShop\Http\Requests\Staff\Catalogue\Product\Option;

use ChingShop\Http\Requests\Staff\StaffRequest;

/**
 * Class PutOptionColour.
 */
class PutSupplierNumber extends StaffRequest
{
    /**
     * @return string
     */
    public function supplierNumber(): string
    {
        return (string) $this->request->get('supplier-number');
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'supplier-number' => sprintf(
                'required|between:3,63|string|unique:%s,%s,%s',
                'product_options',
                'supplier_number',
                $this->get('id')
            ),
        ];
    }
}
