<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Paths\Fields;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem\PathItem;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Map\StringMapEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Map\StringKeyedMapEntry;
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

    protected function toArray(): array
    {
        return [];
    }
}
