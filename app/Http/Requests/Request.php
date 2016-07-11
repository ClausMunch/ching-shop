<?php

namespace ChingShop\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request as HttpRequest;

/**
 * Class Request.
 */
abstract class Request extends FormRequest
{
    /**
     * @param HttpRequest $request
     *
     * @return bool
     */
    abstract public function authorize(HttpRequest $request): bool;
}
