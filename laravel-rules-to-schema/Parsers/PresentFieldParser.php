<?php

declare(strict_types=1);

namespace MohammadAlavi\LaravelRulesToSchema\Parsers;

use MohammadAlavi\LaravelRulesToSchema\Concerns\TracksParserContext;
use MohammadAlavi\LaravelRulesToSchema\Contracts\ContextAwareRuleParser;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Properties\Property;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\LooseFluentDescriptor;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\StrictFluentDescriptor;

final class PresentFieldParser implements ContextAwareRuleParser
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

        foreach ($validationRules as $validationRule) {
            if (!$validationRule->isString()) {
                continue;
            }

            $schema = match ($validationRule->rule) {
                'present' => $schema->required($attribute),
                'present_if' => $this->applyPresentIf($schema, $attribute, $validationRule->args),
                'present_unless' => $this->applyPresentUnless($schema, $attribute, $validationRule->args),
                'present_with' => $this->applyPresentWith($schema, $attribute, $validationRule->args),
                'present_with_all' => $this->applyPresentWithAll($schema, $attribute, $validationRule->args),
                default => $schema,
            };
        }

        return $schema;
    }

    private function applyPresentIf(LooseFluentDescriptor $schema, string $attribute, array $args): LooseFluentDescriptor
    {
        $ifSchema = LooseFluentDescriptor::withoutSchema()
            ->properties(Property::create($args[0], StrictFluentDescriptor::constant($args[1] ?? null)));

        $thenSchema = LooseFluentDescriptor::withoutSchema()->required($attribute);

        return $schema->if($ifSchema)->then($thenSchema);
    }

    private function applyPresentUnless(LooseFluentDescriptor $schema, string $attribute, array $args): LooseFluentDescriptor
    {
        $ifSchema = LooseFluentDescriptor::withoutSchema()
            ->properties(Property::create($args[0], StrictFluentDescriptor::constant($args[1] ?? null)));

        $elseSchema = LooseFluentDescriptor::withoutSchema()->required($attribute);

        return $schema->if($ifSchema)->else($elseSchema);
    }

    private function applyPresentWith(LooseFluentDescriptor $schema, string $attribute, array $args): LooseFluentDescriptor
    {
        $ifSchema = LooseFluentDescriptor::withoutSchema()->required(...$args);
        $thenSchema = LooseFluentDescriptor::withoutSchema()->required($attribute);

        return $schema->if($ifSchema)->then($thenSchema);
    }

    private function applyPresentWithAll(LooseFluentDescriptor $schema, string $attribute, array $args): LooseFluentDescriptor
    {
        $ifSchema = LooseFluentDescriptor::withoutSchema()->required(...$args);
        $thenSchema = LooseFluentDescriptor::withoutSchema()->required($attribute);

        return $schema->if($ifSchema)->then($thenSchema);
    }
}
