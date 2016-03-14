<?php

namespace ChingShop\Http\Requests;

use Illuminate\Http\Request as HttpRequest;

class SetPriceRequest extends Request
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'units'    => 'required|integer|min:0|max:999',
            'subunits' => 'required|integer|min:0|max:99',
        ];
    }
}
