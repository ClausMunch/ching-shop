<?php

namespace ChingShop\Http\Controllers\Customer;

use ChingShop\Http\Controllers\Controller;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class StaticController extends Controller
{
    /** @var ViewFactory */
    private $viewFactory;

    /** @var ResponseFactory */
    private $responseFactory;

    /**
     * ProductController constructor.
     *
     * @param ViewFactory     $viewFactory
     * @param ResponseFactory $responseFactory
     */
    public function __construct(
        ViewFactory $viewFactory,
        ResponseFactory $responseFactory
    ) {
        $this->viewFactory = $viewFactory;
        $this->responseFactory = $responseFactory;
    }

    /**
     * @param string $path
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
