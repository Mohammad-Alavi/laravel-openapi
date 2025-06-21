<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Objects\SecurityRequirementFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityRequirement\SecurityRequirement;
use MohammadAlavi\ObjectOrientedOpenAPI\Utilities\Arr;
use MohammadAlavi\ObjectOrientedOpenAPI\Utilities\Generatable;
use MohammadAlavi\ObjectOrientedOpenAPI\Utilities\ReadonlyGenerator;

final class Security extends Generatable
{
    private readonly array $securityRequirements;

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

    public static function create(SecurityRequirement|SecurityRequirementFactory ...$securityRequirement): self
    {
        // TODO: extract into a builder class
        $securityRequirements = array_map(
            static function (
                SecurityRequirement|SecurityRequirementFactory $securityRequirement,
            ): SecurityRequirement {
                if ($securityRequirement instanceof SecurityRequirement) {
                    return $securityRequirement;
                }

                return $securityRequirement->object();
            },
            $securityRequirement,
        );

        return new self(...$securityRequirements);
    }

    protected function toArray(): array
    {
        return Arr::filter(
            array_map(
                function (SecurityRequirement $securityRequirement): Generatable|ReadonlyGenerator|\stdClass {
                    return $this->toObjectIfEmpty(
                        $securityRequirement,
                    );
                },
                $this->securityRequirements,
            ),
        );
    }
}
