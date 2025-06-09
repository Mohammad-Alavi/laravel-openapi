<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Support;

use MohammadAlavi\LaravelOpenApi\Contracts\Abstract\Factories\Components\ReusableResponseFactory;
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
        Response|ReusableResponseFactory|Reference $response,
    ): self {
        if ($response instanceof ReusableResponseFactory) {
            return new self($name, $response::ref());
        }

        return new self($name, $response);
    }
}
