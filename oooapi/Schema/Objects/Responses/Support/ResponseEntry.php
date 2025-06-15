<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Support;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\ResponseFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Reference\Reference;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response\Response;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Fields\DefaultResponse;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Fields\HTTPStatusCode;
use MohammadAlavi\ObjectOrientedOpenAPI\Utilities\Map\StringMapEntry;

/**
 * @extends StringMapEntry<Response>
 * @extends StringMapEntry<Reference>
 */
final readonly class ResponseEntry extends StringMapEntry
{
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
