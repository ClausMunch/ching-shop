<?php

namespace ChingShop\Http\Requests\Staff\Catalogue\Product;

use ChingShop\Catalogue\Product\ProductOption;
use ChingShop\Http\Requests\Staff\StaffRequest;

/**
 * Class NewProductOptionRequest
 *
 * @package ChingShop\Http\Requests\Staff\Catalogue\Product
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
