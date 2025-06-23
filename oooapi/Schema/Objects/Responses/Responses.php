<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Support\ResponseEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Support\ResponseMap;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Arr;

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

    protected function toArray(): array
    {
        $responses = [];
        foreach ($this->responseMap->entries() as $response) {
            $responses[$response->key()] = $response->value();
        }

        return Arr::filter($responses);
    }
}
