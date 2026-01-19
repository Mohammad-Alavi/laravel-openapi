<?php

namespace MohammadAlavi\LaravelOpenApi\Builders;

use MohammadAlavi\LaravelOpenApi\Contracts\Factories\SecurityFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\Security;
use Webmozart\Assert\Assert;

final readonly class SecurityBuilder
{
    /** @param class-string<SecurityFactory> $factory */
    public function build(string $factory): Security
    {
        Assert::isAOf($factory, SecurityFactory::class);

        return (new $factory())->build();
    }
}
