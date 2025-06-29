<?php

namespace MohammadAlavi\LaravelOpenApi\Builders\Paths\OperationBuilder\Builders;

use MohammadAlavi\LaravelOpenApi\Attributes\Responses as ResponsesAttribute;
use MohammadAlavi\LaravelOpenApi\Contracts\Interface\Factories\ResponsesFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Responses;
use Webmozart\Assert\Assert;

class ResponsesBuilder
{
    public function build(ResponsesAttribute $responsesAttribute): Responses
    {
        Assert::isAOf($responsesAttribute->factory, ResponsesFactory::class);

        /** @var class-string<ResponsesFactory> $factory */
        $factory = $responsesAttribute->factory;

        return (new $factory())->build();
    }
}
