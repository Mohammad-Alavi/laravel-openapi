<?php

namespace MohammadAlavi\LaravelOpenApi\Builders;

use MohammadAlavi\LaravelOpenApi\Contracts\Factories\ExternalDocumentationFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\ExternalDocumentation\ExternalDocumentation;
use Webmozart\Assert\Assert;

final readonly class ExternalDocumentationBuilder
{
    /** @param class-string<ExternalDocumentationFactory> $factory */
    public function build(string $factory): ExternalDocumentation
    {
        Assert::isAOf($factory, ExternalDocumentationFactory::class);

        return (new $factory())->build();
    }
}
