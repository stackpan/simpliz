<?php

namespace App\Http\Controllers\Api\Authentication;

use App\Http\Controllers\Controller;
use App\Services\AuthenticationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
    public function __construct(
        private readonly AuthenticationService $authenticationService,
    )
    {
    }

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): JsonResponse
    {
        $authenticatable = $request->user();

        $this->authenticationService->logout($authenticatable);

        return response()->json([
            'message' => __('message.logout_success'),
        ]);
    }
}
