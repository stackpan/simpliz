<?php

namespace App\Http\Resources;

use App\Traits\HasResourcePaginatedInformation;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UserParticipantCollection extends ResourceCollection
{
    use HasResourcePaginatedInformation;

    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return parent::toArray($request);
    }
}
