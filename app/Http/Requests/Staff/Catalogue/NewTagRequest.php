<?php

namespace ChingShop\Http\Requests\Staff\Catalogue;

use ChingShop\Http\Requests\Staff\StaffRequest;

/**
 * Class NewTagRequest
 *
 * @package ChingShop\Http\Requests\Staff\Catalogue
 */
class NewTagRequest extends StaffRequest
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required|alphanum|string|min:3|max:63|unique:tags',
        ];
    }
}
