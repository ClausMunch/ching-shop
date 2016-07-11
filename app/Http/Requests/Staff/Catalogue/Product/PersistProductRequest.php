<?php

namespace ChingShop\Http\Requests\Staff\Catalogue\Product;

use ChingShop\Http\Requests\Staff\StaffRequest;
use Illuminate\Http\Request as HttpRequest;

/**
 * Class PersistProductRequest.
 */
class PersistProductRequest extends StaffRequest
{
    /** @var array */
    protected $dontFlash = ['new-image.*'];

    const UPDATE_METHODS = [
        HttpRequest::METHOD_PUT,
        HttpRequest::METHOD_PATCH,
    ];

    /**
     * Get the validation rules that apply to the request.
     *
     * @param HttpRequest $request
     *
     * @return array
     */
    public function rules(HttpRequest $request): array
    {
        return [
            'name' => $this->uniqueFieldRules(
                'required|min:3|max:255|unique:products',
                'name',
                $request
            ),
            'sku' => $this->uniqueFieldRules(
                'required|alpha_dash|min:3|max:255|unique:products',
                'sku',
                $request
            ),
            'slug' => $this->uniqueFieldRules(
                'required|alpha_dash|min:5|max:128|unique:products',
                'slug',
                $request
            ),
            'description' => 'required|min:16|max:512',
        ];
    }

    /**
     * @param string      $rules
     * @param string      $fieldName
     * @param HttpRequest $request
     *
     * @return string
     */
    private function uniqueFieldRules(
        string $rules,
        string $fieldName,
        HttpRequest $request
    ) {
        if ($this->requestIsUpdate($request)) {
            $rules = sprintf(
                '%s,%s,%s',
                $rules,
                $fieldName,
                $request->get('id')
            );
        }

        return $rules;
    }

    /**
     * @param HttpRequest $request
     *
     * @return bool
     */
    private function requestIsUpdate(HttpRequest $request): bool
    {
        return in_array($request->method(), self::UPDATE_METHODS, true);
    }
}
