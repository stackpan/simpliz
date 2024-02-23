<?php

namespace App\Http\Resources;

use App\Util\Strings;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->whenNotNull($this->accountable_id, $this->id),
            'accountId' => $this->id,
            'name' => $this->name,
            'firstName' => $this->first_name,
            'lastName' => $this->last_name,
            'email' => $this->email,
            'updatedAt' => $this->updated_at,
            'createdAt' => $this->created_at,
            'type' => Strings::shortenClassName($this->whenHas('accountable_type')),
        ];
    }
}
