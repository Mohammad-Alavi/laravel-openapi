<?php

namespace MohammadAlavi\LaravelRulesToSchema\Parsers;

use MohammadAlavi\LaravelRulesToSchema\Contracts\RuleParser;
use MohammadAlavi\LaravelRulesToSchema\NestedRuleset;
use MohammadAlavi\LaravelRulesToSchema\ParseResult;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\LooseFluentDescriptor;

final readonly class NumericConstraintParser implements RuleParser
{
    public function __invoke(
        string $attribute,
        LooseFluentDescriptor $schema,
        array $validationRules,
        NestedRuleset $nestedRuleset,
    ): ParseResult {
        foreach ($validationRules as $validationRule) {
            if (!$validationRule->isString() || [] === $validationRule->args) {
                continue;
            }

            $value = (int) $validationRule->args[0];

            $schema = match ($validationRule->rule) {
                'multiple_of' => $schema->multipleOf($value),
                'max_digits' => $schema->maximum((int) (10 ** $value - 1)),
                'min_digits' => $schema->minimum($value <= 1 ? 0 : (int) (10 ** ($value - 1))),
                default => $schema,
            };
        }

        return ParseResult::single($schema);
    }
}
