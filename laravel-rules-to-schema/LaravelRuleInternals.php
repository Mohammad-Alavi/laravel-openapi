<?php

declare(strict_types=1);

namespace MohammadAlavi\LaravelRulesToSchema;

use Illuminate\Validation\Rules\Enum as EnumRule;
use Illuminate\Validation\Rules\In as InRule;
use ReflectionProperty;

final readonly class LaravelRuleInternals
{
    /** @return class-string<\UnitEnum> */
    public static function enumType(EnumRule $rule): string
    {
        $property = new ReflectionProperty($rule, 'type');

        return $property->getValue($rule); /** @phpstan-ignore return.type */
    }

    /** @return list<mixed> */
    public static function inValues(InRule $rule): array
    {
        $property = new ReflectionProperty($rule, 'values');

        return $property->getValue($rule); /** @phpstan-ignore return.type */
    }
}
