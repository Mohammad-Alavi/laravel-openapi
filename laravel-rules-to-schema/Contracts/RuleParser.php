<?php

namespace MohammadAlavi\LaravelRulesToSchema\Contracts;

use MohammadAlavi\LaravelRulesToSchema\NestedRuleset;
use MohammadAlavi\LaravelRulesToSchema\ParseResult;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\LooseFluentDescriptor;

interface RuleParser
{
    /** @param list<\MohammadAlavi\LaravelRulesToSchema\ValidationRule> $validationRules */
    public function __invoke(
        string $attribute,
        LooseFluentDescriptor $schema,
        array $validationRules,
        NestedRuleset $nestedRuleset,
    ): ParseResult;
}
