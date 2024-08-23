<?php

namespace App\Http\Resources;

use DragonCode\Support\Facades\Helpers\Arr;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CustomResourceCollection extends AnonymousResourceCollection
{
    /**
     * Add the pagination information to the response.
     *
     * @param  Request  $request
     * @param  array  $paginated
     * @param  array  $default
     * @return array
     */

    public function paginationInformation(Request $request, array $paginated, array $default): array
    {
        return Arr::only($paginated, ['path', 'per_page', 'next_cursor', 'next_page_url', 'prev_cursor', 'prev_page_url']);
    }
}
