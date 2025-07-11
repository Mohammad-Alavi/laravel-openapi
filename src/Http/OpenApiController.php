<?php

namespace MohammadAlavi\LaravelOpenApi\Http;

use MohammadAlavi\LaravelOpenApi\Generator;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\OpenAPI\OpenAPI;

final readonly class OpenApiController
{
    public function show(Generator $generator): OpenAPI
    {
        return $generator->generate();
    }
}
