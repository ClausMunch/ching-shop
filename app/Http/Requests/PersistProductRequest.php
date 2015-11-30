<?php

namespace ChingShop\Http\Requests;

use Illuminate\Http\Request as HttpRequest;

class PersistProductRequest extends Request
{
    const UPDATE_METHODS = [
        HttpRequest::METHOD_PUT,
        HttpRequest::METHOD_PATCH
    ];

    /**
     * Determine if the user is authorized to make this request.
     *
     * @param HttpRequest $request
     * @return bool|bool
     */
    public function authorize(HttpRequest $request): bool
    {
        return $request->user()->isStaff();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @param HttpRequest $request
     * @return array
     */
    public function rules(HttpRequest $request)
    {
        return [
            'name' => $this->nameRules($request),
            'sku'  => $this->skuRules($request)
        ];
    }

    private function nameRules(HttpRequest $request): string
    {
        $rules = 'required|string|min:3|max:255|unique:products';
        if ($this->requestIsUpdate($request)) {
            $rules = sprintf('%s,name,%s', $rules, $request->get('id'));
        }
        return $rules;
    }

    /**
     * @return string
     */
    private function skuRules(HttpRequest $request): string
    {
        $rules = 'required|alpha_dash|min:3|max:255|unique:products';
        if ($this->requestIsUpdate($request)) {
            $rules = sprintf('%s,sku,%s', $rules, $request->get('id'));
        }
        return $rules;
    }

    /**
     * @param HttpRequest $request
     * @return bool
     */
    private function requestIsUpdate(HttpRequest $request): bool
    {
        return in_array($request->method(), self::UPDATE_METHODS);
    }
}
