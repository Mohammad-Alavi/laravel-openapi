<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Support\ResponseCollection;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Support\ResponseEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Utilities\Arr;

// TODO: allow providing default response.
final class Responses extends ExtensibleObject
{
    private function __construct(
        private readonly ResponseCollection $responseCollection,
    ) {
    }

    public static function create(ResponseEntry ...$entry): self
    {
        return new self(
            ResponseCollection::create(...$entry),
        );
    }

    protected function toArray(): array
    {
        $responses = [];
        foreach ($this->responseCollection->entries() as $response) {
            $responses[$response->key()] = $response->value();
        }

        return Arr::filter($responses);
    }
}
