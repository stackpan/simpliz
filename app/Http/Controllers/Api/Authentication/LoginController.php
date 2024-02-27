<?php

namespace App\Http\Controllers\Api\Authentication;

use App\Data\LoginDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Services\AuthenticationService;
use Illuminate\Http\JsonResponse;

class LoginController extends Controller
{
    public function __construct(
        private readonly AuthenticationService $authenticationService,
    )
    {
    }

    public function __invoke(LoginRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $credentials = new LoginDto($validated['username'], $validated['password']);
        $accessToken = $this->authenticationService->authenticate($credentials);

        return response()->json([
            'message' => __('message.login_success'),
            'data' => [
                'token' => $accessToken->token,
                'expiresAt' => $accessToken->expiresAt,
                'scopes' => $accessToken->scopes,
            ]
        ]);
    }
}
