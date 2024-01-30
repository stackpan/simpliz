<?php

namespace App\Exceptions;

use App\Http\Resources\ErrorResponse;
use App\Util\Strings;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\Exceptions\MissingAbilityException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
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

        $this->renderable(function (AccessDeniedHttpException $e) {
            return (new ErrorResponse([], __('message.forbidden')))->response()->setStatusCode(403);
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

    public function render($request, Throwable $e)
    {
        if ($e instanceof ModelNotFoundException) {
            return (new ErrorResponse([], __('message.not_found', ['resource' => Strings::shortenClassName($e->getModel())])))->response()->setStatusCode(404);
        }

        return parent::render($request, $e);
    }
}
