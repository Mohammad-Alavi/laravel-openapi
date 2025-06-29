<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Support\ResponseEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Support\ResponseMap;

// TODO: allow providing default response.
final class Responses extends ExtensibleObject
{
    private function __construct(
        private readonly ResponseMap $responseMap,
    ) {
    }

    public static function create(ResponseEntry ...$entry): self
    {
        return new self(
            ResponseMap::create(...$entry),
        );
    }

    public function toArray(): array
    {
        return $this->responseMap->jsonSerialize() ?? [];
    }
}
