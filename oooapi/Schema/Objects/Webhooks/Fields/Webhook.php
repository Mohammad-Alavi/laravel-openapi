<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Webhooks\Fields;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem\PathItem;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Map\StringKeyedMapEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Map\StringMapEntry;

/**
 * Represents a single webhook entry in the webhooks map.
 *
 * The key is the webhook name (identifier), and the value is a PathItem
 * describing the webhook's request structure.
 *
 * @implements StringMapEntry<PathItem>
 *
 * @see https://spec.openapis.org/oas/v3.2.0#fixed-fields
 */
final class Webhook extends ExtensibleObject implements StringMapEntry
{
    /** @use StringKeyedMapEntry<PathItem> */
    use StringKeyedMapEntry;

    public static function create(string $name, PathItem $pathItem): self
    {
        return new self($name, $pathItem);
    }

    public function toArray(): array
    {
        return [];
    }
}
