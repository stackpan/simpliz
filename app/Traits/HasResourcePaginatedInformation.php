<?php

namespace App\Traits;

trait HasResourcePaginatedInformation
{
    public function paginationInformation($request, $paginated, $default): array
    {
        return [
            'pagination' => [
                'currentPage' => $paginated['current_page'],
                'perPage' => $paginated['per_page'],
                'lastPage' => $paginated['last_page'],
                'total' => $paginated['total'],
            ]
        ];
    }
}
