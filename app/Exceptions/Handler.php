<?php

namespace App\Exceptions;

use App\Helpers\ResponseHelper;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e): Response|JsonResponse|RedirectResponse|\Symfony\Component\HttpFoundation\Response
    {
        if ($request->expectsJson()) {
            if ($e instanceof AuthenticationException) {
                return ResponseHelper::Unauthorized('Authentication required');
            } elseif ($e instanceof AuthorizationException) {
                return ResponseHelper::Forbidden('This action is unauthorized.');
            } elseif ($e instanceof NotFoundHttpException) {
                return ResponseHelper::NotFound('Endpoint not found');
            } elseif ($e instanceof MethodNotAllowedHttpException) {
                return ResponseHelper::MethodNotAllowed('Method not allowed');
            } elseif ($e instanceof ValidationException) {
                return ResponseHelper::BadRequest($e->validator->errors()->first());
            } elseif ($e instanceof ModelNotFoundException) {
                return ResponseHelper::NotFound('Resource not found');
            } else {
                return ResponseHelper::InternalServerError('An unexpected error occurred');
            }
        }

        return parent::render($request, $e);
    }
}
