<?php

declare(strict_types=1);

namespace Tests\Laragen\Support\Doubles\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ResourceWithCasts extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'count' => (int) $this->count,
            'label' => (string) $this->label,
            'price' => (float) $this->price,
            'active' => (bool) $this->active,
            'tags' => (array) $this->tags,
        ];
    }
}
