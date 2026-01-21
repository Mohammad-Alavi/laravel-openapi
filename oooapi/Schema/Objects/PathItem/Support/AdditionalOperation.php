<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem\Support;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Operation\Operation;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Map\StringKeyedMapEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Map\StringMapEntry;
use Webmozart\Assert\Assert;

/**
 * Entry for additional (custom) HTTP method operations.
 *
 * Used for HTTP methods beyond the standard ones defined in HttpMethod enum.
 * The map key preserves the capitalization as it will be sent in the request.
 *
 * This MUST NOT be used for standard HTTP methods (GET, POST, PUT, DELETE,
 * OPTIONS, HEAD, PATCH, TRACE, QUERY) - use AvailableOperation for those.
 *
 * @see https://spec.openapis.org/oas/v3.2.0#path-item-object
 *
 * @implements StringMapEntry<Operation>
 */
final readonly class AdditionalOperation implements StringMapEntry
{
    /** @use StringKeyedMapEntry<Operation> */
    use StringKeyedMapEntry;

    public static function create(string $method, Operation $operation): self
    {
        self::assertNotStandardMethod($method);

        return new self($method, $operation);
    }

    private static function assertNotStandardMethod(string $method): void
    {
        $standardMethods = array_map(
            static fn (HttpMethod $httpMethod): string => $httpMethod->value,
            HttpMethod::cases(),
        );

        Assert::false(
            in_array(strtolower($method), $standardMethods, true),
            sprintf(
                'Standard HTTP method "%s" must use the fixed field (e.g., PathItem->operations()) instead of additionalOperations. '
                . 'additionalOperations is only for custom HTTP methods not defined in the OpenAPI specification.',
                $method,
            ),
        );
    }
}
