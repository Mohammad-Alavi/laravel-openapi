<?php

namespace MohammadAlavi\LaravelOpenApi\Builders\Paths\OperationBuilder\Builders;

use MohammadAlavi\LaravelOpenApi\Contracts\Interface\Factories\SecurityFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\Security;
use Webmozart\Assert\Assert;

readonly class SecurityBuilder
{
    /** @param class-string<SecurityFactory> $securityFactory */
    public function build(string $securityFactory): Security
    {
        Assert::isAOf($securityFactory, SecurityFactory::class);

        return (new $securityFactory())->build();
    }
}
