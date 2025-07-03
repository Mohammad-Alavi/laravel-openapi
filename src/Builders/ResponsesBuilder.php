<?php

namespace MohammadAlavi\LaravelOpenApi\Builders;

use MohammadAlavi\LaravelOpenApi\Contracts\Interface\Factories\ResponsesFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Responses;
use Webmozart\Assert\Assert;

final readonly class ResponsesBuilder
{
    /** @param class-string<ResponsesFactory> $factory */
    public function build(string $factory): Responses
    {
        Assert::isAOf($factory, ResponsesFactory::class);

        return (new $factory())->build();
    }
}
