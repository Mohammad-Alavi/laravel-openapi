<?php

declare(strict_types=1);

namespace MohammadAlavi\LaravelRulesToSchema\Contracts;

use MohammadAlavi\LaravelRulesToSchema\NestedRuleset;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\LooseFluentDescriptor;

interface RuleParser
{
    /**
     * @param list<\MohammadAlavi\LaravelRulesToSchema\ValidationRule> $validationRules
     *
     * @return null|LooseFluentDescriptor|array<string, LooseFluentDescriptor>
     */
    public function __invoke(
        string $attribute,
        LooseFluentDescriptor $schema,
        array $validationRules,
        NestedRuleset $nestedRuleset,
    ): array|LooseFluentDescriptor|null;
}
