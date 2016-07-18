<?php

namespace ChingShop\Http\Requests\Staff\Catalogue\Product\Option;

use ChingShop\Http\Requests\Staff\StaffRequest;
use ChingShop\Modules\Catalogue\Model\Product\ProductOption;
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
