<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\SharedFields\Examples;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\OASObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Example\Example;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Reference\Reference;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Map\StringKeyedMapEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Map\StringMapEntry;

/**
 * @implements StringMapEntry<OASObject>
 */
final readonly class ExampleEntry implements StringMapEntry
{
    /** @use StringKeyedMapEntry<OASObject> */
    use StringKeyedMapEntry;

    public static function create(string $name, Example|Reference $example): self
    {
        return new self($name, $example);
    }
}
