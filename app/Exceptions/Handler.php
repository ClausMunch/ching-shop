<?php

namespace ChingShop\Exceptions;

use ChingShop\Http\WebUi;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use URL;

/**
 * Class Handler.
 */
class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        HttpException::class,
        ModelNotFoundException::class,
    ];

    /**
     * Render an exception into an HTTP response.
     *
     * @param Request    $request
     * @param \Exception $err
     *
     * @return RedirectResponse
     */
    public function render($request, Exception $err)
    {
        $this->log()->notice(
            "{$err->getMessage()} ({$err->getFile()}:{$err->getLine()})"
        );

        if ($err instanceof ModelNotFoundException) {
            $err = new NotFoundHttpException($err->getMessage(), $err);
        }

        if (config('app.debug')
            && $request->user()
            && $request->user()->isStaff()
        ) {
            return $this->renderForStaff($request, $err);
        }

        return parent::render($request, $err);
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        return redirect()->guest('login');
    }

    /**
     * @return LoggerInterface
     */
    private function log(): LoggerInterface
    {
        return $this->container->make(LoggerInterface::class);
    }

    /**
     * @return WebUi
     */
    private function webUi(): WebUi
    {
        return $this->container->make(WebUi::class);
    }

    /**
     * @param Request   $request
     * @param Exception $err
     *
     * @return RedirectResponse
     */
    private function renderForStaff(
        Request $request,
        Exception $err
    ) {
        $this->webUi()->errorMessage(
            sprintf(
                '<strong>Error:</strong>&nbsp;%s (from %s:%s)',
                $err->getMessage(),
                $err->getFile(),
                $err->getLine()
            )
        );

        if (!$request->headers->has('referer')
            || $request->fullUrlIs(URL::previous())
        ) {
            return parent::render($request, $err);
        }

        return $this->webUi()->redirectBack();
    }
}
