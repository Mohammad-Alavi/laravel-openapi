<?php

namespace Tests\Laragen\Support\Doubles\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ResourceWithMethodChains extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'created_date' => $this->created_at->format('Y-m-d'),
            'updated_time' => $this->updated_at->toIso8601String(),
            'resource_name' => $this->resource->name,
        ];
    }
}
