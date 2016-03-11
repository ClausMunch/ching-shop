<?php

namespace ChingShop\Http\Requests;

use Illuminate\Http\Request as HttpRequest;

class PersistProductRequest extends Request
{
    /** @var array */
    protected $dontFlash = ['new-image.*'];

    const UPDATE_METHODS = [
        HttpRequest::METHOD_PUT,
        HttpRequest::METHOD_PATCH,
    ];

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
     * @param HttpRequest $request
     *
     * @return array
     */
    public function rules(HttpRequest $request)
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
            'new-image.*' => 'image|max:5000',
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
    ): string {
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
        return in_array($request->method(), self::UPDATE_METHODS);
    }
}
