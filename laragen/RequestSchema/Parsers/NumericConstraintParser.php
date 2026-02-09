<?php

declare(strict_types=1);

namespace MohammadAlavi\Laragen\RequestSchema\Parsers;

use FluentJsonSchema\FluentSchema;
use LaravelRulesToSchema\Contracts\RuleParser;

final readonly class NumericConstraintParser implements RuleParser
{
    public function __invoke(
        string $attribute,
        FluentSchema $schema,
        array $validationRules,
        array $nestedRuleset,
    ): array|FluentSchema|null {
        foreach ($validationRules as $ruleArgs) {
            [$rule, $args] = $ruleArgs;

            if (!is_string($rule) || [] === $args) {
                continue;
            }

            $value = (int) $args[0];

            match ($rule) {
                'multiple_of' => $schema->number()->multipleOf($value),
                'max_digits' => $schema->number()->maximum((int) (10 ** $value - 1)),
                'min_digits' => $schema->number()->minimum($value <= 1 ? 0 : (int) (10 ** ($value - 1))),
                default => null,
            };
        }

        return $schema;
    }
}
