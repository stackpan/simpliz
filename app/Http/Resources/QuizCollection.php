<?php

namespace App\Http\Resources;

use App\Traits\HasResourceMessageSuccess;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class QuizCollection extends ResourceCollection
{
    use HasResourceMessageSuccess;

    public function paginationInformation($request, $paginated, $default)
    {
        $default['meta']['currentPage'] = $paginated['current_page'];
        unset($default['meta']['current_page']);
        $default['meta']['lastPage'] = $paginated['last_page'];
        unset($default['meta']['last_page']);
        $default['meta']['perPage'] = $paginated['per_page'];
        unset($default['meta']['per_page']);

        return $default;
    }

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
