<?php

namespace MohammadAlavi\LaravelOpenApi\Builders\Paths\OperationBuilder\Builders;

use MohammadAlavi\LaravelOpenApi\Attributes\Responses as ResponsesAttribute;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\ResponsesFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Responses;

class ResponsesBuilder
{
    public function build(ResponsesAttribute $responsesAttribute): Responses
    {
        /** @var ResponsesFactory $factory */
        $factory = app($responsesAttribute->factory);

        return $factory->build();
    }
}
