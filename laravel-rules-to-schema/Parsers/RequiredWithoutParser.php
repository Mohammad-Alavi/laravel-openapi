<?php

declare(strict_types=1);

namespace MohammadAlavi\LaravelRulesToSchema\Parsers;

use MohammadAlavi\LaravelRulesToSchema\Concerns\TracksParserContext;
use MohammadAlavi\LaravelRulesToSchema\Contracts\ContextAwareRuleParser;
use MohammadAlavi\LaravelRulesToSchema\ValidationRuleNormalizer;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\LooseFluentDescriptor;

final class RequiredWithoutParser implements ContextAwareRuleParser
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

        $hasRequiredWithout = [];
        foreach ($this->allRules as $attr => $ruleSet) {
            foreach ($ruleSet[ValidationRuleNormalizer::RULES_KEY] as $validationRule) {
                if ('required_without' === $validationRule->rule) {
                    $hasRequiredWithout[$attr] = $validationRule->args;
                }
            }
        }

        if ([] === $hasRequiredWithout) {
            return $schema;
        }

        if (array_key_last($this->allRules) === $attribute) {
            $conditions = [];
            foreach ($hasRequiredWithout as $attr => $args) {
                $notSchema = LooseFluentDescriptor::withoutSchema()->required(...$args);
                $ifSchema = LooseFluentDescriptor::withoutSchema()->not($notSchema);
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
