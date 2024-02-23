<?php

namespace App\Http\Controllers\Api\Authentication;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

class GetCurrentController extends Controller
{
    public function __invoke(Request $request): UserResource
    {
        $user = $request->user();

        return (new UserResource($user))
            ->additional([
                'message' => __('message.success'),
            ]);
    }
}
