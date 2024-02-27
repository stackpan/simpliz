<?php

namespace App\Exceptions;

use App\Http\Resources\ErrorResponse;
use Illuminate\Http\Exceptions\HttpResponseException;

class UnauthorizedException extends HttpResponseException
{
    public function __construct(?string $message = null)
    {
        if (!$message) $message = __('message.unauthorized');
        $response = (new ErrorResponse([], $message))->response()->setStatusCode(401);
        parent::__construct($response);
    }
}
