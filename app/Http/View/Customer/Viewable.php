<?php

namespace ChingShop\Http\View\Customer;

/**
 * Interface Viewable.
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
