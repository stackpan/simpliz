<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait HasResourceMessageSuccess
{
    public function with(Request $request): array
    {
        return [
            'message' => __('message.success'),
        ];
    }
}
