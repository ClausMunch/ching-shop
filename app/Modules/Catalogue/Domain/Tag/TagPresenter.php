<?php

namespace ChingShop\Modules\Catalogue\Domain\Tag;

use ChingShop\Http\View\Staff\HttpCrudInterface;
use McCool\LaravelAutoPresenter\BasePresenter;

/**
 * Class TagPresenter.
 */
class TagPresenter extends BasePresenter implements HttpCrudInterface
{
    /** @var Tag */
    protected $wrappedObject;

    /**
     * TagPresenter constructor.
     *
     * @param Tag $resource
     */
    public function __construct(Tag $resource)
    {
        parent::__construct($resource);
        $this->wrappedObject = $resource;
    }

    /**
     * Whether this resource has already been persisted.
     *
     * @return bool
     */
    public function isStored(): bool
    {
        return (bool) $this->wrappedObject->id;
    }

    /**
     * Routing name prefix for persisting this resource.
     *
     * @return string
     */
    public function routePath(): string
    {
        return 'tags';
    }

    /**
     * Identifier used when persisting this resource.
     *
     * @return string
     */
    public function crudId(): string
    {
        return (string) $this->wrappedObject->id;
    }
}
