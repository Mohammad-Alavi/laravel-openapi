<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Support\Serialization;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\MediaType\MediaType;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Map\StringKeyedMap;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Map\StringMap;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Content\ContentEntry;

/**
 * @implements StringMap<MediaType>
 */
final readonly class Content implements SerializationRule, StringMap
{
    /** @use StringKeyedMap<MediaType> */
    use StringKeyedMap {
        StringKeyedMap::jsonSerialize as jsonSerializeTrait;
    }

    public static function create(ContentEntry $contentEntry): self
    {
        return self::put($contentEntry);
    }

    public function jsonSerialize(): array
    {
        return ['content' => $this->jsonSerializeTrait()];
    }
}
