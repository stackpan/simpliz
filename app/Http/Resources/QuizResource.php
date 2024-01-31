<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuizResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=> $this->id,
            'name'=> $this->name,
            'description'=> $this->description,
            'duration'=> $this->duration,
            'maxAttempts'=> $this->max_attempts ? (integer) $this->max_attempts : null,
            'color'=> $this->color->name,
            'status'=> $this->status,
            'createdBy'=> [
                'proctorId' => $this->createdBy->id,
                'name' => $this->createdBy->account->name,
            ],
            'createdAt'=> $this->created_at,
            'updatedAt'=> $this->updated_at,
            'attemptCount' => $this->whenPivotLoaded('participant_quiz', fn () => $this->pivot->attempt_count),
            'highestScore' => $this->whenPivotLoaded('participant_quiz', fn () => $this->highest_score),
        ];
    }
}
