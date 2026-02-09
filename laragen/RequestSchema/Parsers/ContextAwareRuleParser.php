<?php

declare(strict_types=1);

namespace MohammadAlavi\Laragen\RequestSchema\Parsers;

use FluentJsonSchema\FluentSchema;
use LaravelRulesToSchema\Contracts\RuleParser;

interface ContextAwareRuleParser extends RuleParser
{
    /**
     * @param array<string, mixed> $allRules
     */
    public function withContext(FluentSchema $baseSchema, array $allRules, string|null $request): static;
}
