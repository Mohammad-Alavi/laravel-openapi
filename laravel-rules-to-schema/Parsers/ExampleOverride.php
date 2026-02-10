<?php

declare(strict_types=1);

namespace MohammadAlavi\LaravelRulesToSchema\Parsers;

use MohammadAlavi\LaravelRulesToSchema\Concerns\TracksParserContext;
use MohammadAlavi\LaravelRulesToSchema\Contracts\ContextAwareRuleParser;
use MohammadAlavi\LaravelRulesToSchema\Contracts\ExampleProvider;
use MohammadAlavi\LaravelRulesToSchema\NestedRuleset;
use MohammadAlavi\LaravelRulesToSchema\ParseResult;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\LooseFluentDescriptor;

final class ExampleOverride implements ContextAwareRuleParser
{
    use TracksParserContext;

    public function __construct(
        private readonly ExampleProvider|null $exampleProvider = null,
    ) {
    }

    public function __invoke(
        string $attribute,
        LooseFluentDescriptor $schema,
        array $validationRules,
        NestedRuleset $nestedRuleset,
    ): ParseResult {
        if (null === $this->baseSchema || null === $this->allRules || null === $this->exampleProvider) {
            return ParseResult::single($schema);
        }

        foreach ($validationRules as $validationRule) {
            $ruleName = $validationRule->name();

            if ($this->exampleProvider->has($ruleName)) {
                $example = $this->exampleProvider->get($ruleName);
                $currentExamples = $schema->getExamples() ?? [];
                $schema = $schema->examples(...[...$currentExamples, ...$example]);
            }
        }

        return ParseResult::single($schema);
    }
}
