<?php

namespace Tests\Laragen\Support\Doubles\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ResourceWithLiterals extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'is_verified' => true,
            'is_banned' => false,
            'deleted_at' => null,
            'type' => 'user',
        ];
    }
}
