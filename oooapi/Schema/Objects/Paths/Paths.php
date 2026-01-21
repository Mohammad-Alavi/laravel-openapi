<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Paths;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Paths\Fields\Path;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Map\StringKeyedMap;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Map\StringMap;

/**
 * Paths Object.
 *
 * Holds the relative paths to the individual endpoints and their operations.
 * The path is appended to the URL from the Server Object to construct the
 * full URL. Field pattern: /{path}
 *
 * @see https://spec.openapis.org/oas/v3.2.0#paths-object
 *
 * @implements StringMap<Path>
 */
final class Paths extends ExtensibleObject implements StringMap
{
    /** @use StringKeyedMap<Path> */
    use StringKeyedMap {
        StringKeyedMap::jsonSerialize as jsonSerializeTrait;
    }

    public static function create(Path ...$path): self
    {
        return self::put(...$path);
    }

    public function toArray(): array
    {
        return $this->jsonSerialize() ?? [];
    }

    public function jsonSerialize(): array
    {
        return $this->jsonSerializeTrait() ?? [];
    }
}
