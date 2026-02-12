<?php

namespace MohammadAlavi\LaravelRulesToSchema\Parsers;

use MohammadAlavi\LaravelRulesToSchema\Concerns\TracksParserContext;
use MohammadAlavi\LaravelRulesToSchema\Contracts\ContextAwareRuleParser;
use MohammadAlavi\LaravelRulesToSchema\NestedRuleset;
use MohammadAlavi\LaravelRulesToSchema\ParseResult;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Properties\Property;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\LooseFluentDescriptor;

final class RequiredWithoutParser implements ContextAwareRuleParser
{
    use TracksParserContext;

    public function __invoke(
        string $attribute,
        LooseFluentDescriptor $schema,
        array $validationRules,
        NestedRuleset $nestedRuleset,
    ): ParseResult {
        if (null === $this->baseSchema || null === $this->allRules) {
            return ParseResult::single($schema);
        }

        $hasRequiredWithout = [];
        foreach ($this->allRules as $attr => $ruleSet) {
            foreach ($ruleSet->validationRules as $validationRule) {
                if ('required_without' === $validationRule->rule) {
                    $hasRequiredWithout[$attr] = $validationRule->args;
                }
            }
        }

        if ([] === $hasRequiredWithout) {
            return ParseResult::single($schema);
        }

        if (array_key_last($this->allRules) === $attribute) {
            $conditions = [];
            foreach ($hasRequiredWithout as $attr => $args) {
                $notSchema = LooseFluentDescriptor::withoutSchema()
                    ->properties(...array_map(
                        static fn (string $name): Property => Property::create($name, LooseFluentDescriptor::withoutSchema()),
                        $args,
                    ))
                    ->required(...$args);
                $ifSchema = LooseFluentDescriptor::withoutSchema()->not($notSchema);
                $thenSchema = LooseFluentDescriptor::withoutSchema()
                    ->properties(Property::create($attr, LooseFluentDescriptor::withoutSchema()))
                    ->required($attr);

                $conditions[] = LooseFluentDescriptor::withoutSchema()
                    ->if($ifSchema)
                    ->then($thenSchema);
            }

            $this->modifiedBase = $this->baseSchema->allOf(...[...$this->baseSchema->getAllOf() ?? [], ...$conditions]);
        }

        return ParseResult::single($schema);
    }
}
