<?php

namespace ChingShop\Http\View\Staff;

use Illuminate\Routing\Router;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpFoundation\Request;
use Illuminate\Contracts\Routing\UrlGenerator;

/**
 * Class LocationComposer
 * Location logic for views.
 */
class LocationComposer
{
    const ACTIVE_CLASS = 'active';
    const ROUTE_UPDATE = 'update';
    const ROUTE_STORE = 'store';
    const ROUTE_SHOW = 'show';
    const ROUTE_DELETE = 'destroy';
    const ROUTE_DETACH = 'detach';

    /** @var Router */
    private $router;

    /** @var UrlGenerator */
    private $urlGenerator;

    /**
     * @param Router       $router
     * @param UrlGenerator $urlGenerator
     */
    public function __construct(Router $router, UrlGenerator $urlGenerator)
    {
        $this->router = $router;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * Bind a Location object to the view.
     *
     * @param View $view
     */
    public function compose(View $view)
    {
        $view->with(['location' => $this]);
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return (string) $this->router->currentRouteName();
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function is(string $name): bool
    {
        return $this->name() === $name;
    }

    /**
     * @param string $parentName
     *
     * @return bool
     */
    public function isIn(string $parentName): bool
    {
        $parentParts = explode('.', $parentName);
        $currentParts = $this->parts();
        foreach ($parentParts as $i => $parentPart) {
            if (empty($currentParts[$i]) || $currentParts[$i] !== $parentPart) {
                return false;
            }
        }

        return true;
    }

    /**
     * Returns 'active' if given location
     * is part of current location
     * (useful in views).
     *
     * @param string $location
     *
     * @return string
     */
    public function putActive(string $location)
    {
        return $this->putIfIn(self::ACTIVE_CLASS, $location);
    }

    /**
     * Return the given string if current location
     * exactly matches given location
     * (useful in views).
     *
     * @param string $content
     * @param string $locationName
     *
     * @return string
     */
    public function putIfIs(string $content, string $locationName)
    {
        if ($this->is($locationName)) {
            return $content;
        }

        return '';
    }

    /**
     * Return the given string if given location
     * is part of current location
     * (useful in views).
     *
     * @param string $content
     * @param string $parentName
     *
     * @return string
     */
    public function putIfIn(string $content, string $parentName)
    {
        if ($this->isIn($parentName)) {
            return $content;
        }

        return '';
    }

    /**
     * @param HttpCrudInterface $crud
     *
     * @return string
     */
    public function showHrefFor(HttpCrudInterface $crud): string
    {
        return $this->urlGenerator->route(
            implode('.', [$crud->routePath(), self::ROUTE_SHOW]),
            $crud->crudID()
        );
    }

    /**
     * @param HttpCrudInterface $crud
     *
     * @return string
     */
    public function persistMethodFor(HttpCrudInterface $crud): string
    {
        return $crud->isStored() ?
            Request::METHOD_PUT : Request::METHOD_POST;
    }

    /**
     * @param HttpCrudInterface $crud
     *
     * @return string
     */
    public function persistActionFor(HttpCrudInterface $crud): string
    {
        if ($crud->isStored()) {
            return $this->urlGenerator->route(
                implode('.', [$crud->routePath(), self::ROUTE_UPDATE]),
                $crud->crudID()
            );
        }

        return $this->urlGenerator->route(
            implode('.', [$crud->routePath(), self::ROUTE_STORE])
        );
    }

    /**
     * @param HttpCrudInterface $crud
     *
     * @return string
     */
    public function deleteActionFor(HttpCrudInterface $crud): string
    {
        return $this->urlGenerator->route(
            implode('.', [$crud->routePath(), self::ROUTE_DELETE]),
            $crud->crudID()
        );
    }

    /**
     * @param RelaterInterface $relater
     * @param Model $related
     * @return string
     */
    public function detachActionFor(
        RelaterInterface $relater,
        Model $related
    ): string {
        return $this->urlGenerator->route(
            implode('.', [
                $relater->routePath(),
                self::ROUTE_DETACH,
                $relater->relationKeyTo($related)
            ]),
            [
                'productId' => $relater->id(),
                'imageId'   => $related->getAttribute('id')
            ]
        );
    }

    /**
     * @return array
     */
    private function parts(): array
    {
        return (array) explode('.', $this->name());
    }
}
