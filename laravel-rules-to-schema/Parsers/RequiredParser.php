<?php

declare(strict_types=1);

namespace MohammadAlavi\LaravelRulesToSchema\Parsers;

use MohammadAlavi\LaravelRulesToSchema\Contracts\RuleParser;
use MohammadAlavi\LaravelRulesToSchema\NestedRuleset;
use MohammadAlavi\LaravelRulesToSchema\ParseResult;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\LooseFluentDescriptor;

/**
 * Required tracking is handled by the orchestrator (RuleToSchema).
 * This parser exists only to filter out fields with `sometimes` rule.
 */
final readonly class RequiredParser implements RuleParser
{
    public function __invoke(
        string $attribute,
        LooseFluentDescriptor $schema,
        array $validationRules,
        NestedRuleset $nestedRuleset,
    ): ParseResult {
        foreach ($validationRules as $validationRule) {
            if (!$validationRule->isString()) {
                continue;
            }

            if ('sometimes' === $validationRule->rule) {
                return ParseResult::single($schema);
            }
        }

        return ParseResult::single($schema);
    }
}
