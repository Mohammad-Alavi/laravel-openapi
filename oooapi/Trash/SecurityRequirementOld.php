<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Trash;

use Illuminate\Support\Collection;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\SimpleCreator;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\SecurityScheme;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\SimpleCreatorTrait;
use MohammadAlavi\ObjectOrientedOpenAPI\Utilities\Arr;

class SecurityRequirementOld extends ExtensibleObject implements SimpleCreator
{
    use SimpleCreatorTrait;

    protected string|null $securityScheme = null;

    /** @var array<array-key, SecurityScheme|array<array-key, SecurityScheme>> */
    protected array|null $nestedSecurityScheme = null;

    /** @var string[]|null */
    protected array|null $scopes = null;

    public function nestedSecurityScheme(array $nestedSecurityScheme): self
    {
        $clone = clone $this;

        $clone->nestedSecurityScheme = $nestedSecurityScheme;

        return $clone;
    }

    public function scopes(string ...$scope): static
    {
        $clone = clone $this;

        $clone->scopes = [] !== $scope ? $scope : null;

        return $clone;
    }

    private function generateNestedAuth(): array
    {
        // TODO: maybe skip generating if empty?
        $spec = collect($this->nestedSecurityScheme)->map(
            static function (SecurityScheme|string|array $securityScheme) {
                if ($securityScheme instanceof SecurityScheme) {
                    return self::create()->securityScheme($securityScheme)->toArray();
                }

                if (is_string($securityScheme)) {
                    return self::create()->securityScheme($securityScheme)->toArray();
                }

                return collect($securityScheme)->map(
                    static fn (SecurityScheme $securityScheme): array => self::create()
                        ->securityScheme($securityScheme)
                        ->toArray(),
                )->collapse();
            },
        );

        // merge "and" & "or" security schemes based on https://swagger.io/docs/specification/authentication/
        $fixedSpec = $spec->reduce(static function (Collection $carry, Collection|array $item) {
            if (count($item) > 1) {
                return $carry->add(collect($item)->reduce(
                    static fn (Collection $carry, array $item) => $carry->merge($item),
                    collect(),
                ));
            }

            return $carry->merge($item);
        }, collect());

        return $fixedSpec->toArray();
    }

    protected function toArray(): array
    {
        if ([] !== $this->nestedSecurityScheme && !is_null($this->nestedSecurityScheme)) {
            return $this->generateNestedAuth();
        }

        if ([] !== $this->scopes && !is_null($this->scopes)) {
            /** @var SecurityScheme $scheme */
            $scheme = app($this->securityScheme);

            return Arr::filter([
                $scheme->key() => [
                    'scopes' => $this->scopes,
                ],
            ]);
        }

        if (is_null($this->securityScheme)) {
            return [];
        }

        /** @var SecurityScheme $scheme */
        $scheme = app($this->securityScheme);

        return [Arr::filter([
            $scheme->key() => [],
        ])];
    }

    public function securityScheme(SecurityScheme|string|null $securityScheme): static
    {
        if ($securityScheme instanceof SecurityScheme) {
            $securityScheme = $securityScheme::class;
        }

        $clone = clone $this;

        $clone->securityScheme = $securityScheme;

        return $clone;
    }
}
