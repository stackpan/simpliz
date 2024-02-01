<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ParticipantResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'accountId' => $this->account->id,
            'name' => $this->account->name,
            'firstName' => $this->account->first_name,
            'lastName' => $this->account->last_name,
            'email' => $this->account->email,
            'updatedAt' => $this->account->updated_at,
            'createdAt' => $this->account->created_at,
        ];
    }
}
