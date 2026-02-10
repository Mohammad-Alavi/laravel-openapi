<?php

declare(strict_types=1);

namespace MohammadAlavi\LaravelRulesToSchema\Contracts;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\LooseFluentDescriptor;

interface ContextAwareRuleParser extends RuleParser
{
    /**
     * @param array<string, \MohammadAlavi\LaravelRulesToSchema\NestedRuleset> $allRules
     */
    public function withContext(LooseFluentDescriptor $baseSchema, array $allRules, string|null $request): static;

    /**
     * Returns a modified base schema if this parser needs to alter the root schema
     * (e.g., adding allOf conditions). The orchestrator reads this after each invocation.
     */
    public function modifiedBaseSchema(): LooseFluentDescriptor|null;
}
