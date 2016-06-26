<?php

namespace ChingShop\Http\Requests\Staff\Catalogue;

use ChingShop\Http\Requests\Staff\StaffRequest;

/**
 * Class ImageOrderRequest.
 */
class ImageOrderRequest extends StaffRequest
{
    /**
     * @return array
     */
    public function imageOrder(): array
    {
        return (array) ($this->json('imageOrder') ?: $this->get('imageOrder'));
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'imageOrder'   => 'present|array',
            'imageOrder.*' => 'integer',
        ];
    }
}
