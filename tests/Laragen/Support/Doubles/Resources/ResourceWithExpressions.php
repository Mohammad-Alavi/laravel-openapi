<?php

declare(strict_types=1);

namespace Tests\Laragen\Support\Doubles\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ResourceWithExpressions extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'full_name' => $this->first_name . ' ' . $this->last_name,
            'total_cents' => $this->price * 100,
            'half' => $this->total / 2,
            'sum' => $this->a + $this->b,
            'diff' => $this->a - $this->b,
            'remainder' => $this->count % 10,
            'is_inactive' => !$this->active,
            'is_adult' => $this->age > 18,
            'is_same' => $this->a === $this->b,
            'status_label' => match ($this->status) {
                'active' => 'Active',
                'inactive' => 'Inactive',
                default => 'Unknown',
            },
        ];
    }
}
