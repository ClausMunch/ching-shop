<?php

namespace ChingShop\Http\Controllers\Customer;

use ChingShop\Http\Controllers\Controller;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class StaticController.
 */
class StaticController extends Controller
{
    /** @var ViewFactory */
    private $viewFactory;

    /**
     * ProductController constructor.
     *
     * @param ViewFactory $viewFactory
     */
    public function __construct(ViewFactory $viewFactory)
    {
        $this->viewFactory = $viewFactory;
    }

    /**
     * @param string $path
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return View|Response
     */
    public function pageAction(string $path)
    {
        $slug = str_limit($path, 63, '');

        if ($this->staticViewExists($slug)) {
            return $this->makeStaticView($slug);
        }

        throw new NotFoundHttpException();
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    private function staticViewExists(string $name): bool
    {
        return $this->viewFactory->exists($this->staticViewName($name));
    }

    /**
     * @param string $name
     *
     * @return \Illuminate\Contracts\View\View
     */
    private function makeStaticView(string $name): View
    {
        return $this->viewFactory->make($this->staticViewName($name));
    }

    /**
     * @param string $name
     *
     * @return string
     */
    private function staticViewName(string $name): string
    {
        return "customer.static.pages.{$name}";
    }
}
