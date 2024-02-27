<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ErrorResponse extends ResourceCollection
{
    private string $message;

    public function __construct($resource, string $message)
    {
        parent::__construct($resource);
        $this->message = $message;
    }

    public static $wrap = 'errors';

    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'errors' => $this->collection,
        ];
    }

    public function with(Request $request): array
    {
        return [
            'message' => $this->message,
        ];
    }
}
