<?php

namespace ChingShop\Http\Requests\Staff\Catalogue;

use ChingShop\Http\Requests\Staff\StaffRequest;

class NewImagesRequest extends StaffRequest
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'new-image.*' => 'image|max:5000|mimes:jpeg',
        ];
    }
}
