<?php

namespace ChingShop\Http;

use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Laracasts\Flash\FlashNotifier;

/**
 * Class WebUi.
 */
class WebUi
{
    /** @var ViewFactory */
    private $viewFactory;

    /** @var ResponseFactory */
    private $responseFactory;

    /** @var FlashNotifier */
    private $flashNotifier;

    /**
     * WebUi constructor.
     *
     * @param ViewFactory     $viewFactory
     * @param ResponseFactory $responseFactory
     * @param FlashNotifier   $flashNotifier
     */
    public function __construct(
        ViewFactory $viewFactory,
        ResponseFactory $responseFactory,
        FlashNotifier $flashNotifier
    ) {
        $this->viewFactory = $viewFactory;
        $this->responseFactory = $responseFactory;
        $this->flashNotifier = $flashNotifier;
    }

    /**
     * @param string $view
     * @param array  $data
     * @param array  $mergeData
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function view(string $view, array $data = [], array $mergeData = [])
    {
        return $this->viewFactory->make($view, $data, $mergeData);
    }

    /**
     * @param string $route
     * @param array  $parameters
     * @param int    $status
     * @param array  $headers
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirect(
        string $route,
        array $parameters = [],
        int $status = 302,
        array $headers = []
    ) {
        return $this->responseFactory->redirectToRoute(
            $route,
            $parameters,
            $status,
            $headers
        );
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectBack()
    {
        return redirect()->back();
    }

    /**
     * @param array $data
     * @param int   $status
     * @param array $headers
     * @param int   $options
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function json(
        array $data = [],
        int $status = 200,
        array $headers = [],
        int $options = 0
    ) {
        return $this->responseFactory->json($data, $status, $headers, $options);
    }

    /**
     * @param string $message
     */
    public function successMessage(string $message)
    {
        $this->flashNotifier->success($message);
    }

    /**
     * @param string $message
     */
    public function infoMessage(string $message)
    {
        $this->flashNotifier->info($message);
    }

    /**
     * @param string $message
     */
    public function errorMessage(string $message)
    {
        $this->flashNotifier->error($message);
    }

    /**
     * @param string $message
     */
    public function warningMessage(string $message)
    {
        $this->flashNotifier->warning($message);
    }

    /**
     * @param string $url
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectAway(string $url)
    {
        return redirect()->away($url);
    }
}
