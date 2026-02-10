<?php

declare(strict_types=1);

namespace MohammadAlavi\LaravelRulesToSchema\Parsers;

use MohammadAlavi\LaravelRulesToSchema\Concerns\TracksParserContext;
use MohammadAlavi\LaravelRulesToSchema\Contracts\ContextAwareRuleParser;
use MohammadAlavi\LaravelRulesToSchema\NestedRuleset;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\BooleanSchema;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Properties\Property;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\LooseFluentDescriptor;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\StrictFluentDescriptor;

final class ConditionalProhibitedParser implements ContextAwareRuleParser
{
    use TracksParserContext;

    public function __invoke(
        string $attribute,
        LooseFluentDescriptor $schema,
        array $validationRules,
        NestedRuleset $nestedRuleset,
    ): array|LooseFluentDescriptor|null {
        if (null === $this->baseSchema || null === $this->allRules) {
            return $schema;
        }

        foreach ($validationRules as $validationRule) {
            if (!$validationRule->isString()) {
                continue;
            }

            $schema = match ($validationRule->rule) {
                'prohibited_if' => $this->applyProhibitedIf($schema, $attribute, $validationRule->args),
                'prohibited_unless' => $this->applyProhibitedUnless($schema, $attribute, $validationRule->args),
                'prohibits' => $this->applyProhibits($schema, $attribute, $validationRule->args),
                default => $schema,
            };
        }

        return $schema;
    }

    private function applyProhibitedIf(LooseFluentDescriptor $schema, string $attribute, array $args): LooseFluentDescriptor
    {
        $ifSchema = LooseFluentDescriptor::withoutSchema()
            ->properties(Property::create($args[0], StrictFluentDescriptor::constant($args[1] ?? null)));

        return $schema->if($ifSchema)->then(LooseFluentDescriptor::withoutSchema()->not(BooleanSchema::true()));
    }

    private function applyProhibitedUnless(LooseFluentDescriptor $schema, string $attribute, array $args): LooseFluentDescriptor
    {
        $ifSchema = LooseFluentDescriptor::withoutSchema()
            ->properties(Property::create($args[0], StrictFluentDescriptor::constant($args[1] ?? null)));

        return $schema->if($ifSchema)->else(LooseFluentDescriptor::withoutSchema()->not(BooleanSchema::true()));
    }

    private function applyProhibits(LooseFluentDescriptor $schema, string $attribute, array $args): LooseFluentDescriptor
    {
        return $schema
            ->if(LooseFluentDescriptor::withoutSchema()->required($attribute))
            ->then(LooseFluentDescriptor::withoutSchema()->not(BooleanSchema::true()));
    }
}
