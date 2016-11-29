<?php

namespace ChingShop\Modules\Sales\Http\Requests\Staff;

use ChingShop\Http\Requests\Staff\StaffRequest;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Collection;

/**
 * Request to create or update a sales offer.
 */
class PersistOfferRequest extends StaffRequest
{
    const RULES = [
        'price'      => [
            'integer',
            'between:1,99999',
            'required_without:percentage',
        ],
        'percentage' => [
            'integer',
            'between:1,99',
            'required_without:price',
        ],
        'quantity'   => ['integer', 'between:0,999'],
        'effect'     => ['required', 'string', 'in:relative,absolute'],
        'code'       => ['alphanum', 'between:2,31'],
        'colour'     => ['required', 'string', 'unique:offers,colour'],
    ];

    /**
     * @param HttpRequest $request
     *
     * @return array
     */
    public function rules(HttpRequest $request): array
    {
        if ($request->getMethod() === HttpRequest::METHOD_POST) {
            return self::RULES;
        }

        $rules = self::RULES;
        $rules['colour'][2] .= ",{$request->get('id')}";

        return $rules;
    }

    /**
     * @return array|Collection
     */
    public function all()
    {
        return (new Collection(parent::all()))->reject(
            function ($value, $key) {
                return in_array($key, ['price', 'percentage'], true)
                    && (string) $value === '';
            }
        )->all();
    }
}
