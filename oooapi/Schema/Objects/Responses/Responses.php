<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Support\ResponseEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Support\ResponseMap;

/**
 * Responses Object.
 *
 * A container for the expected responses of an operation. The container
 * maps an HTTP response code to the expected response. At least one
 * response code is required, and it SHOULD be the success response.
 *
 * @see https://spec.openapis.org/oas/v3.1.0#responses-object
 */
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
