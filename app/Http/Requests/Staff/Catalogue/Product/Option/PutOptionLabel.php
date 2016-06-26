<?php

namespace ChingShop\Http\Requests\Staff\Catalogue\Product\Option;

use ChingShop\Catalogue\Product\ProductOption;
use ChingShop\Http\Requests\Staff\StaffRequest;
use Illuminate\Http\Request;

/**
 * Class PutOptionLabel.
 */
class PutOptionLabel extends StaffRequest
{
    /**
     * @return string
     */
    public function label(): string
    {
        return (string) $this->request->get('label');
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    public function rules(Request $request): array
    {
        return ProductOption::validationRules(
            (int) $request->route('productId'),
            (int) $request->route('optionId')
        );
    }
}
