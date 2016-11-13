<?php

namespace ChingShop\Modules\Sales\Http\Requests\Customer;

use ChingShop\Http\Requests\Request;

/**
 * Request from the Stripe payment form after Stripe checkout has been
 * completed.
 */
class StripePaymentRequest extends Request
{
    const TOKEN = 'stripeToken';

    /**
     * @return string
     */
    public function stripeToken(): string
    {
        return (string) $this->get(self::TOKEN);
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            self::TOKEN => 'required|string',
        ];
    }
}
