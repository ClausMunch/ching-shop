<?php

namespace ChingShop\Exceptions;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
     * @param \Illuminate\Http\Request $request
     * @param \Exception               $err
     *
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $err)
    {
        $this->log()->notice(
            "{$err->getMessage()} ({$err->getFile()}:{$err->getLine()})"
        );

        if ($err instanceof ModelNotFoundException) {
            $err = new NotFoundHttpException($err->getMessage(), $err);
        }

        return parent::render($request, $err);
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param \Illuminate\Http\Request $request
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
}
