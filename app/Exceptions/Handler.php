<?php

namespace ChingShop\Exceptions;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class Handler
 *
 * @package ChingShop\Exceptions
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
        if ($err instanceof ModelNotFoundException) {
            $err = new NotFoundHttpException($err->getMessage(), $err);
        }

        return parent::render($request, $err);
    }
}
