<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

// Collection paginée de publications
class PublicationCollection extends ResourceCollection
{
    public $collects = PublicationResource::class;

    public function toArray($request)
    {
        return [
            'data' => $this->collection,
        ];
    }

    public function with($request)
    {
        return [
            'meta' => [
                'per_page' => $this->resource->perPage(),
                'current_page' => $this->resource->currentPage(),
                'total' => $this->resource->total(),
            ],
        ];
    }
}
