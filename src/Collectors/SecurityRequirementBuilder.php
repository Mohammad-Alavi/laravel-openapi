<?php

namespace MohammadAlavi\LaravelOpenApi\Collectors;

use MohammadAlavi\LaravelOpenApi\Factories\Component\SecuritySchemeFactory;
use MohammadAlavi\LaravelOpenApi\Objects\SecurityRequirement;
use MohammadAlavi\LaravelOpenApi\SecuritySchemes\DefaultSecurityScheme;
use MohammadAlavi\LaravelOpenApi\SecuritySchemes\PublicSecurityScheme;
use MohammadAlavi\ObjectOrientedOAS\Exceptions\InvalidArgumentException;
use MohammadAlavi\ObjectOrientedOAS\Objects\SecurityScheme;

class SecurityRequirementBuilder
{
    /**
     * @throws InvalidArgumentException
     */
    public function build(string|array|null $securitySchemeFactories): SecurityRequirement
    {
        if (is_null($securitySchemeFactories) || '' === $securitySchemeFactories) {
            return $this->buildSecurityRequirementFrom(DefaultSecurityScheme::class);
        }

        if ([] === $securitySchemeFactories) {
            return $this->buildSecurityRequirementFrom(PublicSecurityScheme::class);
        }

        if ($this->isSingleAuthStringSecurity($securitySchemeFactories)) {
            return $this->buildSecurityRequirementFrom($securitySchemeFactories);
        }

        if ($this->isSingleAuthArraySecurity($securitySchemeFactories)) {
            if ($this->hasSingleArraySecurityWithin($securitySchemeFactories)) {
                return $this->buildSecurityRequirementFrom($securitySchemeFactories[0][0]);
            }

            return $this->buildSecurityRequirementFrom($securitySchemeFactories[0]);
        }

        if ($this->isMultiAuthArraySecurity($securitySchemeFactories)) {
            return $this->buildSecurityRequirementFrom($securitySchemeFactories);
        }

        if ($this->isMultiAuthArraySecurity($securitySchemeFactories[0])) {
            return $this->buildSecurityRequirementFrom($securitySchemeFactories);
        }

        throw new \RuntimeException('Invalid security configuration');
    }

    /**
     * @param class-string<SecuritySchemeFactory>|array<array-key, class-string<SecuritySchemeFactory>|array<array-key, class-string<SecuritySchemeFactory>>> $factories
     *
     * @throws InvalidArgumentException
     */
    private function buildSecurityRequirementFrom(string|array $factories): SecurityRequirement
    {
        if (is_string($factories)) {
            /** @var SecuritySchemeFactory $factory */
            $factory = app($factories);
            $securityScheme = $factory->build();

            return SecurityRequirement::create()->securityScheme($securityScheme);
        }

        return SecurityRequirement::create()->multiAuthSecurityScheme($this->buildMultiAuthSecuritySchemeFrom($factories));
    }

    /**
     * @param array<array-key, class-string<SecuritySchemeFactory>|array<array-key, class-string<SecuritySchemeFactory>>> $securitySchemeFactories
     *
     * @return array<array-key, SecurityScheme|array<array-key, SecurityScheme>>
     */
    private function buildMultiAuthSecuritySchemeFrom(array $securitySchemeFactories): array
    {
        // TODO: cannot have "no security e.g. []" while providing multiple other securities
        // iterate over all $this->securitySchemeFactories items and check if any of them are NoSecurity
        // throw new \Exception('Cannot disable security while providing multiple other securities');
        return collect($securitySchemeFactories)
            // if item is a string, then we have to AND the security items
            // if item is an array, then we have to OR the security items
            ->map(static function ($factory) {
                if (is_a($factory, SecuritySchemeFactory::class, true)) {
                    return app($factory)->build();
                }

                return collect($factory)
                    ->map(static fn (string $securitySchemeFactory): SecurityScheme => app($securitySchemeFactory)->build());
            })->toArray();
    }

    private function isSingleAuthStringSecurity(array|string|null $security): bool
    {
        return is_string($security);
    }

    private function isSingleAuthArraySecurity(array|string|null $security): bool
    {
        return !$this->isSingleAuthStringSecurity($security)
            && is_countable($security)
            && 1 === count($security)
            && (is_string($security[0]) || $this->hasSingleArraySecurityWithin($security));
    }

    private function hasSingleArraySecurityWithin(array|string|null $value): bool
    {
        return is_array($value[0]) && 1 === count($value[0]) && is_string($value[0][0]);
    }

    private function isMultiAuthArraySecurity(array|string|null $security): bool
    {
        return !$this->isSingleAuthArraySecurity($security)
            && is_countable($security)
            && count($security) > 1;
    }
}
