<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Paths\Fields;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem\PathItem;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Map\StringKeyedMapEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Map\StringMapEntry;
use Webmozart\Assert\Assert;

/**
 * Path entry in the Paths Object.
 *
 * A relative path to an individual endpoint. The field name MUST begin
 * with a forward slash (/). Path templating is allowed.
 *
 * @see https://spec.openapis.org/oas/v3.2.0#paths-object
 *
 * @implements StringMapEntry<PathItem>
 */
final class Path extends ExtensibleObject implements StringMapEntry
{
    /** @use StringKeyedMapEntry<PathItem> */
    use StringKeyedMapEntry;

    public static function create(string $path, PathItem $pathItem): self
    {
        Assert::startsWith($path, '/');

        return new self($path, $pathItem);
    }

    public function toArray(): array
    {
        return [];
    }
}
