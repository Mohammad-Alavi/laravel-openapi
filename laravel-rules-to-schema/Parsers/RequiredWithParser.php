<?php

declare(strict_types=1);

namespace MohammadAlavi\LaravelRulesToSchema\Parsers;

use MohammadAlavi\LaravelRulesToSchema\Concerns\TracksParserContext;
use MohammadAlavi\LaravelRulesToSchema\Contracts\ContextAwareRuleParser;
use MohammadAlavi\LaravelRulesToSchema\ValidationRuleNormalizer;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\LooseFluentDescriptor;

final class RequiredWithParser implements ContextAwareRuleParser
{
    use TracksParserContext;

    public function __invoke(
        string $attribute,
        LooseFluentDescriptor $schema,
        array $validationRules,
        array $nestedRuleset,
    ): array|LooseFluentDescriptor|null {
        if (null === $this->baseSchema || null === $this->allRules) {
            return $schema;
        }

        $hasRequiredWith = [];
        foreach ($this->allRules as $attr => $ruleSet) {
            foreach ($ruleSet[ValidationRuleNormalizer::RULES_KEY] as $validationRule) {
                if ('required_with' === $validationRule->rule) {
                    $hasRequiredWith[$attr] = $validationRule->args;
                }
            }
        }

        if ([] === $hasRequiredWith) {
            return $schema;
        }

        if (array_key_last($this->allRules) === $attribute) {
            $conditions = [];
            foreach ($hasRequiredWith as $attr => $args) {
                if (1 === count($args)) {
                    $ifSchema = LooseFluentDescriptor::withoutSchema()->required(...$args);
                } else {
                    $anyOfConditions = [];
                    foreach ($args as $arg) {
                        $anyOfConditions[] = LooseFluentDescriptor::withoutSchema()->required($arg);
                    }
                    $ifSchema = LooseFluentDescriptor::withoutSchema()->anyOf(...$anyOfConditions);
                }

                $thenSchema = LooseFluentDescriptor::withoutSchema()->required($attr);

                $conditions[] = LooseFluentDescriptor::withoutSchema()
                    ->if($ifSchema)
                    ->then($thenSchema);
            }

            $this->modifiedBase = $this->baseSchema->allOf(...[...$this->baseSchema->getAllOf() ?? [], ...$conditions]);
        }

        return $schema;
    }
}
