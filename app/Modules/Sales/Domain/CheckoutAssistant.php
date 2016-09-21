<?php

namespace ChingShop\Modules\Sales\Domain;

/**
 * Class CheckoutAssistant.
 */
class CheckoutAssistant
{
    /** @var Clerk */
    private $clerk;

    /**
     * @param Clerk $clerk
     */
    public function __construct(Clerk $clerk)
    {
        $this->clerk = $clerk;
    }

    /**
     * @param array $addressFields
     *
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     *
     * @return Address
     */
    public function saveAddress(array $addressFields): Address
    {
        $address = $this->clerk->basket()->address ?? new Address();
        $address->fill($addressFields);
        $address->save();

        $this->clerk->basket()->address()->associate($address);
        $this->clerk->basket()->save();

        return $address;
    }
}
