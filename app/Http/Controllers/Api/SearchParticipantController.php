<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserCollection;
use App\Services\ParticipantService;
use Illuminate\Http\Request;

class SearchParticipantController extends Controller
{
    public function __construct(
        private readonly ParticipantService $participantService,
    )
    {
    }

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): UserCollection
    {
        $search = $request->query('search', '');
        $limit = $request->query('limit', 10);

        $results = $this->participantService->search($search, $limit);
        return new UserCollection($results);
    }
}
