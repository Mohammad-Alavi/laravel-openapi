<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\OAuth\SecurityRequirementFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Utilities\Arr;
use MohammadAlavi\ObjectOrientedOpenAPI\Utilities\Generatable;

final class Security extends Generatable
{
    private array $securityRequirements;

    private function __construct(
        SecurityRequirement ...$securityRequirement,
    ) {
        $this->securityRequirements = $securityRequirement;
    }

    public function merge(self $security): self
    {
        return self::create(
            ...$this->securityRequirements,
            ...$security->securityRequirements,
        );
    }

    public static function create(SecurityRequirementFactory ...$securityRequirementFactory): self
    {
        // TODO: extract into a builder class
        $securityRequirements = array_map(
            static fn (
                SecurityRequirementFactory $securityRequirementFactory,
            ): SecurityRequirement => $securityRequirementFactory->build(),
            $securityRequirementFactory,
        );

        return new self(...$securityRequirements);
    }

    protected function toArray(): array
    {
        return Arr::filter(
            array_map(
                fn (SecurityRequirement $securityRequirement) => $this->toObjectIfEmpty(
                    $securityRequirement,
                ),
                $this->securityRequirements,
            ),
        );
    }
}
