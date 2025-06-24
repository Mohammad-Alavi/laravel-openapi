<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Support;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\ResponseFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\OASObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Reference\Reference;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response\Response;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Fields\DefaultResponse;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Fields\HTTPStatusCode;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Map\StringMapEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Map\StringKeyedMapEntry;

/**
 * @implements StringMapEntry<OASObject>
 */
final readonly class ResponseEntry implements StringMapEntry
{
    /** @use StringKeyedMapEntry<OASObject> */
    use StringKeyedMapEntry;

    public static function create(
        DefaultResponse|HTTPStatusCode $name,
        Response|ResponseFactory|Reference $response,
    ): self {
        if ($response instanceof ResponseFactory) {
            return new self($name, $response::reference());
        }

        return new self($name, $response);
    }
}
