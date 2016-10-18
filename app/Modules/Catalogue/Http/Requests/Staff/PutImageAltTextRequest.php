<?php

namespace ChingShop\Modules\Catalogue\Http\Requests\Staff;

use ChingShop\Http\Requests\Staff\StaffRequest;

/**
 * Request to update the alt text of an image.
 */
class PutImageAltTextRequest extends StaffRequest
{
    const QUERY_PARAM = 'alt-text';

    /**
     * @return string
     */
    public function altText(): string
    {
        return (string) $this->get(self::QUERY_PARAM);
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            self::QUERY_PARAM => 'required|string|between:3,255',
        ];
    }
}
