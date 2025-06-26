<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\SharedFields\Callbacks;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\CallbackFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\OASObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Callback\Callback;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Reference\Reference;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Map\StringKeyedMapEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Map\StringMapEntry;

/**
 * @implements StringMapEntry<OASObject>
 */
final readonly class CallbackEntry implements StringMapEntry
{
    /** @use StringKeyedMapEntry<OASObject> */
    use StringKeyedMapEntry;

    public static function create(string $name, Callback|CallbackFactory|Reference $callback): self
    {
        return new self($name, $callback);
    }
}
