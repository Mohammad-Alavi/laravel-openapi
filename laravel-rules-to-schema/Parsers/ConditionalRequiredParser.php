<?php

declare(strict_types=1);

namespace MohammadAlavi\LaravelRulesToSchema\Parsers;

use MohammadAlavi\LaravelRulesToSchema\Concerns\TracksParserContext;
use MohammadAlavi\LaravelRulesToSchema\Contracts\ContextAwareRuleParser;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Properties\Property;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\LooseFluentDescriptor;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\StrictFluentDescriptor;

final class ConditionalRequiredParser implements ContextAwareRuleParser
{
    use TracksParserContext;

    private const RULES = [
        'required_if',
        'required_unless',
        'required_with_all',
        'required_without_all',
        'required_if_accepted',
        'required_if_declined',
    ];

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
            if (!$validationRule->isString() || !in_array($validationRule->rule, self::RULES, true)) {
                continue;
            }

            $schema = $this->applyConditional($schema, $attribute, $validationRule->rule, $validationRule->args);
        }

        return $schema;
    }

    private function applyConditional(LooseFluentDescriptor $schema, string $attribute, string $rule, array $args): LooseFluentDescriptor
    {
        $thenSchema = LooseFluentDescriptor::withoutSchema()->required($attribute);

        return match ($rule) {
            'required_if' => $this->applyRequiredIf($schema, $thenSchema, $args),
            'required_unless' => $this->applyRequiredUnless($schema, $thenSchema, $args),
            'required_with_all' => $this->applyRequiredWithAll($schema, $thenSchema, $args),
            'required_without_all' => $this->applyRequiredWithoutAll($schema, $thenSchema, $args),
            'required_if_accepted' => $this->applyRequiredIfAccepted($schema, $thenSchema, $args),
            'required_if_declined' => $this->applyRequiredIfDeclined($schema, $thenSchema, $args),
            default => $schema,
        };
    }

    private function applyRequiredIf(LooseFluentDescriptor $schema, LooseFluentDescriptor $thenSchema, array $args): LooseFluentDescriptor
    {
        $ifSchema = LooseFluentDescriptor::withoutSchema()
            ->properties(Property::create($args[0], StrictFluentDescriptor::constant($args[1] ?? null)));

        return $schema->if($ifSchema)->then($thenSchema);
    }

    private function applyRequiredUnless(LooseFluentDescriptor $schema, LooseFluentDescriptor $thenSchema, array $args): LooseFluentDescriptor
    {
        $ifSchema = LooseFluentDescriptor::withoutSchema()
            ->properties(Property::create($args[0], StrictFluentDescriptor::constant($args[1] ?? null)));

        return $schema->if($ifSchema)->else($thenSchema);
    }

    private function applyRequiredWithAll(LooseFluentDescriptor $schema, LooseFluentDescriptor $thenSchema, array $args): LooseFluentDescriptor
    {
        $ifSchema = LooseFluentDescriptor::withoutSchema()->required(...$args);

        return $schema->if($ifSchema)->then($thenSchema);
    }

    private function applyRequiredWithoutAll(LooseFluentDescriptor $schema, LooseFluentDescriptor $thenSchema, array $args): LooseFluentDescriptor
    {
        $notSchema = LooseFluentDescriptor::withoutSchema()->required(...$args);
        $ifSchema = LooseFluentDescriptor::withoutSchema()->not($notSchema);

        return $schema->if($ifSchema)->then($thenSchema);
    }

    private function applyRequiredIfAccepted(LooseFluentDescriptor $schema, LooseFluentDescriptor $thenSchema, array $args): LooseFluentDescriptor
    {
        $ifSchema = LooseFluentDescriptor::withoutSchema()
            ->properties(Property::create($args[0], StrictFluentDescriptor::constant(true)));

        return $schema->if($ifSchema)->then($thenSchema);
    }

    private function applyRequiredIfDeclined(LooseFluentDescriptor $schema, LooseFluentDescriptor $thenSchema, array $args): LooseFluentDescriptor
    {
        $ifSchema = LooseFluentDescriptor::withoutSchema()
            ->properties(Property::create($args[0], StrictFluentDescriptor::constant(false)));

        return $schema->if($ifSchema)->then($thenSchema);
    }
}
