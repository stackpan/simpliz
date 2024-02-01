<?php

namespace App\Traits;

trait HasResourcePaginatedInformation
{
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
}
