<?php

declare(strict_types=1);

namespace Tests\Laragen\Support\Doubles\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ResourceWithNestedArray extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'meta' => [
                'created_at' => $this->created_at,
                'version' => 1,
            ],
        ];
    }
}
