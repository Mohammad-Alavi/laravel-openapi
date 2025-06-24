<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Paths;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Paths\Fields\Path;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Map\StringMap;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Map\StringKeyedMap;

/**
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

    public function jsonSerialize(): array
    {
        return $this->jsonSerializeTrait() ?? [];
    }

    protected function toArray(): array
    {
        return $this->jsonSerialize() ?? [];
    }
}
