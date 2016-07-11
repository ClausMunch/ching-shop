<?php

namespace ChingShop\Http\View\Staff;

/**
 * Interface HttpCrudInterface.
 */
interface HttpCrudInterface
{
    /**
     * Whether this resource has already been persisted.
     *
     * @return bool
     */
    public function isStored(): bool;

    /**
     * Routing name prefix for persisting this resource.
     *
     * @return string
     */
    public function routePath(): string;

    /**
     * Identifier used when persisting this resource.
     *
     * @return string
     */
    public function crudId(): string;
}
