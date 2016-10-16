<?php

namespace ChingShop\Modules\Catalogue\Http\Requests;

use ChingShop\Http\Requests\Request;

/**
 * A catalogue product search request.
 */
class SearchRequest extends Request
{
    const QUERY_PARAMETER = 'query';

    /**
     * @return string
     */
    public function searchQuery(): string
    {
        return (string) $this->get(self::QUERY_PARAMETER);
    }

    /**
     * @return array
     */
    public function rules()
    {
        return ['query' => 'required|string'];
    }
}
