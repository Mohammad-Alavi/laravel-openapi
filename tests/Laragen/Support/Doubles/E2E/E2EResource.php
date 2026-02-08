<?php

declare(strict_types=1);

namespace Tests\Laragen\Support\Doubles\E2E;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class E2EResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'type' => 'article',
            'is_published' => $this->is_published,
        ];
    }
}
