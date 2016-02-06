<?php

namespace ChingShop\Http\View\Staff;

interface HttpCrud
{
    /**
     * Whether this resource has already been persisted
     * @return bool
     */
    public function isStored(): bool;

    /**
     * Routing name prefix for persisting this resource
     * @return string
     */
    public function crudRoutePrefix(): string;

    /**
     * Identifier used when persisting this resource
     * @return string
     */
    public function crudID(): string;
}
