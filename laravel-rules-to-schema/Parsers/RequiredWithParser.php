<?php

declare(strict_types=1);

namespace MohammadAlavi\LaravelRulesToSchema\Parsers;

use MohammadAlavi\LaravelRulesToSchema\Concerns\TracksParserContext;
use MohammadAlavi\LaravelRulesToSchema\Contracts\ContextAwareRuleParser;
use MohammadAlavi\LaravelRulesToSchema\NestedRuleset;
use MohammadAlavi\LaravelRulesToSchema\ParseResult;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Properties\Property;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\LooseFluentDescriptor;

final class RequiredWithParser implements ContextAwareRuleParser
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

        $hasRequiredWith = [];
        foreach ($this->allRules as $attr => $ruleSet) {
            foreach ($ruleSet->validationRules as $validationRule) {
                if ('required_with' === $validationRule->rule) {
                    $hasRequiredWith[$attr] = $validationRule->args;
                }
            }
        }

        if ([] === $hasRequiredWith) {
            return ParseResult::single($schema);
        }

        if (array_key_last($this->allRules) === $attribute) {
            $conditions = [];
            foreach ($hasRequiredWith as $attr => $args) {
                if (1 === count($args)) {
                    $ifSchema = LooseFluentDescriptor::withoutSchema()
                        ->properties(...array_map(
                            static fn (string $name): Property => Property::create($name, LooseFluentDescriptor::withoutSchema()),
                            $args,
                        ))
                        ->required(...$args);
                } else {
                    $anyOfConditions = [];
                    foreach ($args as $arg) {
                        $anyOfConditions[] = LooseFluentDescriptor::withoutSchema()
                            ->properties(Property::create($arg, LooseFluentDescriptor::withoutSchema()))
                            ->required($arg);
                    }
                    $ifSchema = LooseFluentDescriptor::withoutSchema()->anyOf(...$anyOfConditions);
                }

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
