<?php

namespace Aistglobal\Conversation\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Aistglobal\Conversation\Exceptions\API\APIException;
use Aistglobal\Conversation\Exceptions\API\NotFoundAPIException;
use Aistglobal\Conversation\Exceptions\API\ValidationAPIException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        ResourceNotFoundException::class,
        APIException::class,
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof ResourceNotFoundException) {
            abort(404);
        }

        if ($exception instanceof NotFoundHttpException) {
            throw new NotFoundAPIException();
        }

        if ($exception instanceof ValidationException) {
            throw new ValidationAPIException($exception);
        }

        return parent::render($request, $exception);
    }
}
