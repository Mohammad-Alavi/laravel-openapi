<?php

namespace MohammadAlavi\LaravelOpenApi\Objects;

use MohammadAlavi\ObjectOrientedOAS\Objects\SecurityRequirement as ParentSecurityRequirement;
use MohammadAlavi\ObjectOrientedOAS\Objects\SecurityScheme;
use Illuminate\Support\Collection;

class SecurityRequirement extends ParentSecurityRequirement
{
    /**
     * @var array<array-key, SecurityScheme|array<array-key, SecurityScheme>>
     */
    protected array $multiAuthSecurityScheme = [];

    /**
     * @param array<array-key, SecurityScheme|array<array-key, SecurityScheme>> $multiAuthSecurityScheme
     */
    public function multiAuthSecurityScheme(array $multiAuthSecurityScheme): self
    {
        $instance = clone $this;

        $instance->multiAuthSecurityScheme = $multiAuthSecurityScheme;

        return $instance;
    }

    protected function generate(): array
    {
        if (!empty($this->multiAuthSecurityScheme)) {
            return $this->generateMultiAuth();
        }

        return [parent::generate()];
    }

    private function generateMultiAuth(): array
    {
        // TODO: maybe skip generating if empty?
        $spec = collect($this->multiAuthSecurityScheme)->map(
            static function ($securityScheme) {
                if ($securityScheme instanceof SecurityScheme) {
                    return self::create()->securityScheme($securityScheme)->generate();
                }

                return collect($securityScheme)->map(
                    static fn (SecurityScheme $securityScheme): array => self::create()->securityScheme($securityScheme)->generate(),
                )->collapse();
            },
        );

        // merge "and" & "or" security schemes based on https://swagger.io/docs/specification/authentication/
        $fixedSpec = $spec->reduce(static function (Collection $carry, $item) {
            if (count($item) > 1) {
                return $carry->add($item->reduce(
                    static fn (Collection $carry, array $item) => $carry->merge($item),
                    collect(),
                ));
            }

            return $carry->merge($item);
        }, collect());

        return $fixedSpec->toArray();
    }
}
