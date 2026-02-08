<?php

declare(strict_types=1);

namespace Tests\Laragen\Support\Doubles\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ResourceWithFunctionCalls extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'upper_name' => strtoupper($this->name),
            'trimmed' => trim($this->bio),
            'computed' => strtoupper('static_value'),
        ];
    }
}
