<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Composable\SecurityRequirementFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Generatable;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\ReadonlyGeneratable;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityRequirement\SecurityRequirement;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Arr;

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

    public function toArray(): array
    {
        return Arr::filter(
            array_map(
                function (SecurityRequirement $securityRequirement): Generatable|ReadonlyGeneratable|\stdClass {
                    return $this->toObjectIfEmpty(
                        $securityRequirement,
                    );
                },
                $this->securityRequirements,
            ),
        );
    }
}
