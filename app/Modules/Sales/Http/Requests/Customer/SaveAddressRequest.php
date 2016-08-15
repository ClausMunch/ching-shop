<?php

namespace ChingShop\Modules\Sales\Http\Requests\Customer;

use ChingShop\Http\Requests\Request;
use ChingShop\Modules\Data\Model\Country;
use Illuminate\Http\Request as HttpRequest;

class SaveAddressRequest extends Request
{
    /**
     * @param HttpRequest $request
     *
     * @return bool
     */
    public function authorize(HttpRequest $request): bool
    {
        return true;
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'name'         => 'required|string|min:2|max:255',
            'line_one'     => 'required|string|min:2|max:255',
            'line_two'     => 'string|max:255',
            'city'         => 'required|string|min:2|max:255',
            'post_code'    => 'required|string|min:3|max:31',
            'country_code' => 'required|in:' . Country::codesString(),
        ];
    }

    /**
     * @return string[]
     */
    public function getAddressFields(): array
    {
        return array_filter(
            $this->all(),
            function (string $key) {
                return array_key_exists($key, $this->rules());
            },
            ARRAY_FILTER_USE_KEY
        );
    }
}
