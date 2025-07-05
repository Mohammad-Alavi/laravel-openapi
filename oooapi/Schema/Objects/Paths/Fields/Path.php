<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Paths\Fields;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem\PathItem;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Map\StringKeyedMapEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Map\StringMapEntry;
use Webmozart\Assert\Assert;

/**
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
