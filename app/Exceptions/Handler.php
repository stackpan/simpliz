<?php

namespace App\Exceptions;

use App\Http\Resources\ApiResponse;
use App\Http\Resources\ErrorResponse;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
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

        $this->renderable(function (ValidationException $e) {
            $errors = $e->errors();
            return (new ErrorResponse($errors, __('message.bad_request')))->response()->setStatusCode(400);
        });

        $this->renderable(function (Throwable $e) {
            if ($e instanceof HttpResponseException) {
                return $e->getResponse();
            }

            if (!config('app.debug')) {
                return (new ErrorResponse([], __('message.server_error')))->response()->setStatusCode(500);
            }
        });
    }
}
