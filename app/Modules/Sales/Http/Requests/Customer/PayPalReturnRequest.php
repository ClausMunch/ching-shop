<?php

namespace ChingShop\Modules\Sales\Http\Requests\Customer;

use ChingShop\Http\Requests\Request;

/**
 * Class PayPalReturnRequest
 * @package ChingShop\Modules\Sales\Http\Requests\Customer
 */
class PayPalReturnRequest extends Request
{
    /**
     * @return bool
     */
    public function isSuccessful(): bool
    {
        return (bool) $this->get('token');
    }

    /**
     * @return string
     */
    public function paymentId(): string
    {
        return (string) $this->get('paymentId');
    }

    /**
     * @return string
     */
    public function payerId(): string
    {
        return (string) $this->get('PayerID');
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [];
    }
}
