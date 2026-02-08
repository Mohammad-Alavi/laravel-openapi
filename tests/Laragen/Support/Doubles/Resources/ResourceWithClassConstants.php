<?php

declare(strict_types=1);

namespace Tests\Laragen\Support\Doubles\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ResourceWithClassConstants extends JsonResource
{
    public const TYPE_ADMIN = 'admin';

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type' => self::TYPE_ADMIN,
            'unresolvable' => SomeNonExistentClass::VALUE,
        ];
    }
}
