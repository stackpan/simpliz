<?php

namespace App\Http\Resources;

use App\Traits\HasResourceMessageSuccess;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class OptionCollection extends ResourceCollection
{
    use HasResourceMessageSuccess;

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
