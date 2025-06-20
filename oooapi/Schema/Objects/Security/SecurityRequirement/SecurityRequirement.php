<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityRequirement;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\OAuth\Scope;
use MohammadAlavi\ObjectOrientedOpenAPI\Utilities\Arr;
use MohammadAlavi\ObjectOrientedOpenAPI\Utilities\ReadonlyGenerator;

final readonly class SecurityRequirement extends ReadonlyGenerator
{
    /**
     * @param RequiredSecurity[] $requiredSecurities
     */
    private function __construct(
        private array $requiredSecurities,
    ) {
    }

    public static function create(RequiredSecurity ...$requiredSecurity): self
    {
        return new self($requiredSecurity);
    }

    protected function toArray(): array
    {
        $requiredSecurities = [];
        foreach ($this->requiredSecurities as $security) {
            $requiredSecurities[$security->scheme()] = array_map(
                static fn (Scope $scope): string => $scope->name(),
                $security->scopes(),
            );
        }

        return Arr::filter($requiredSecurities);
    }
}
