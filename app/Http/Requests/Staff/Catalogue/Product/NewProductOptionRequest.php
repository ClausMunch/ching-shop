<?php

namespace ChingShop\Http\Requests\Staff\Catalogue\Product;

use ChingShop\Http\Requests\Staff\StaffRequest;
use ChingShop\Modules\Catalogue\Domain\Product\ProductOption;

/**
 * Class NewProductOptionRequest.
 */
class NewProductOptionRequest extends StaffRequest
{
    /**
     * @return int
     */
    public function productId(): int
    {
        return (int) $this->request->get('option-product');
    }

    /**
     * @return string
     */
    public function label(): string
    {
        return (string) $this->request->get('label');
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return array_merge(
            ProductOption::validationRules(
                (int) $this->request->get('option-product')
            ),
            ['option-product' => 'required|int|exists:products,id']
        );
    }
}
