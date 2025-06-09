<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Support;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Reference\Reference;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response\Response;
use MohammadAlavi\ObjectOrientedOpenAPI\Utilities\Map\StringMap;

/**
 * @extends StringMap<Response>
 * @extends StringMap<Reference>
 */
final readonly class ResponseCollection extends StringMap
{
    public static function create(ResponseEntry ...$entry): self
    {
        return parent::put(...$entry);
    }
}
