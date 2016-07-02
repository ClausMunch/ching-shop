<?php

namespace ChingShop\Http\Requests\Staff\Catalogue\Product\Option;

use ChingShop\Http\Requests\Staff\StaffRequest;

class PutOptionColour extends StaffRequest
{
    /**
     * @return int
     */
    public function colourId(): int
    {
        return (int) $this->request->get('colour');
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'colour' => 'required|integer|exists:colours,id',
        ];
    }
}
