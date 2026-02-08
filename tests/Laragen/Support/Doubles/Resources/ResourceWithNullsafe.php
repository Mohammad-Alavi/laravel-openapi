<?php

declare(strict_types=1);

namespace Tests\Laragen\Support\Doubles\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ResourceWithNullsafe extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'relation_name' => $this->relation?->name,
            'formatted_date' => $this->relation?->format('Y-m-d'),
        ];
    }
}
