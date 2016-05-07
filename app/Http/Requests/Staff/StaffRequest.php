<?php

namespace ChingShop\Http\Requests\Staff;

use ChingShop\Http\Requests\Request;
use Illuminate\Http\Request as HttpRequest;

class StaffRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @param HttpRequest $request
     *
     * @return bool|bool
     */
    public function authorize(HttpRequest $request): bool
    {
        return $request->user()->isStaff();
    }
}
