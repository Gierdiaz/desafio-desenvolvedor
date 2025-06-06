<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ContentCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data'   => $this->collection,
            '_links' => [
                'self'  => $this->url($this->currentPage()),
                'first' => $this->url(1),
                'last'  => $this->url($this->lastPage()),
                'prev'  => $this->previousPageUrl(),
                'next'  => $this->nextPageUrl(),
            ],
            '_meta' => [
                'current_page' => $this->currentPage(),
                'total'        => $this->total(),
                'per_page'     => $this->perPage(),
            ],
        ];
    }
}
