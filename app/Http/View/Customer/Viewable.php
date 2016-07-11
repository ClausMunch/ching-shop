<?php

namespace ChingShop\Http\View\Customer;

/**
 * Interface Viewable
 *
 * @package ChingShop\Http\View\Customer
 */
interface Viewable
{
    /**
     * @return string: the routing prefix for this entity
     */
    public function routePrefix(): string;

    /**
     * @return array of location key => value
     */
    public function locationParts(): array;
}
