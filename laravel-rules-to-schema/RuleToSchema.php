<?php

declare(strict_types=1);

namespace MohammadAlavi\LaravelRulesToSchema;

use MohammadAlavi\LaravelRulesToSchema\Contracts\ContextAwareRuleParser;
use MohammadAlavi\LaravelRulesToSchema\Contracts\RuleParser;
use MohammadAlavi\LaravelRulesToSchema\Parsers\NestedObjectParser;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Properties\Property;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Type;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\LooseFluentDescriptor;

final class RuleToSchema
{
    /** @param list<class-string<RuleParser>> $parsers */
    public function __construct(
        private readonly array $parsers,
        private readonly array $customRuleSchemas = [],
    ) {
    }

    /** @param array<string, NestedRuleset> $normalizedRuleSets */
    public function transform(array $normalizedRuleSets, string|null $request = null): LooseFluentDescriptor
    {
        $baseSchema = LooseFluentDescriptor::withoutSchema()->type(Type::object());

        // Phase 1: Regular parsers — build property schemas independently
        $properties = [];
        $requiredFields = [];

        foreach ($normalizedRuleSets as $propertyName => $rawRules) {
            $result = $this->parseRuleset($propertyName, $rawRules);

            if (null === $result) {
                continue;
            }

            // Track required fields
            $validationRules = $rawRules->validationRules;
            if ($this->isRequired($validationRules)) {
                $requiredFields[] = $propertyName;
            }

            if ($result instanceof LooseFluentDescriptor) {
                $properties[] = Property::create($propertyName, $result);
            } elseif (is_array($result)) {
                foreach ($result as $key => $schema) {
                    $properties[] = Property::create($key, $schema);
                    // If the original field is required, expanded fields (e.g. confirmed) are also required
                    if ($this->isRequired($validationRules) && $key !== $propertyName) {
                        $requiredFields[] = $key;
                    }
                }
            }
        }

        if ([] !== $properties) {
            $baseSchema = $baseSchema->properties(...$properties);
        }

        $requiredFields = array_values(array_unique($requiredFields));
        if ([] !== $requiredFields) {
            $baseSchema = $baseSchema->required(...$requiredFields);
        }

        // Phase 2: Context-aware parsers — can modify the base schema
        foreach ($normalizedRuleSets as $propertyName => $rawRules) {
            $baseSchema = $this->parseContextAwareRules(
                $propertyName,
                $rawRules,
                $baseSchema,
                $normalizedRuleSets,
                $request,
            );
        }

        return $baseSchema;
    }

    private function parseRuleset(string $name, NestedRuleset $nestedRuleset): LooseFluentDescriptor|array|null
    {
        $validationRules = $nestedRuleset->validationRules;

        $schemas = [$name => LooseFluentDescriptor::withoutSchema()];

        foreach ($this->parsers as $parserClass) {
            $instance = $this->resolveParser($parserClass);

            if ($instance instanceof ContextAwareRuleParser) {
                continue;
            }

            if ($instance instanceof NestedObjectParser) {
                $instance = $instance->withParseRuleset($this->parseRuleset(...));
            }

            $newSchemas = [];

            foreach ($schemas as $schemaKey => $schema) {
                $resultSchema = $instance($schemaKey, $schema, $validationRules, $nestedRuleset);

                if (null === $resultSchema) {
                    continue;
                }

                if (is_array($resultSchema)) {
                    $newSchemas = [...$newSchemas, ...$resultSchema];
                } else {
                    $newSchemas[$schemaKey] = $resultSchema;
                }
            }

            $schemas = $newSchemas;
        }

        if (0 === count($schemas)) {
            return null;
        }
        if (1 === count($schemas)) {
            return array_values($schemas)[0];
        }

        return $schemas;
    }

    private function parseContextAwareRules(
        string $name,
        NestedRuleset $nestedRuleset,
        LooseFluentDescriptor $baseSchema,
        array $allRuleSets,
        string|null $request,
    ): LooseFluentDescriptor {
        $validationRules = $nestedRuleset->validationRules;

        foreach ($this->parsers as $parserClass) {
            $instance = $this->resolveParser($parserClass);

            if (!$instance instanceof ContextAwareRuleParser) {
                continue;
            }

            $contextualParser = $instance->withContext($baseSchema, $allRuleSets, $request);

            $contextualParser(
                $name,
                $this->findPropertySchema($baseSchema, $name) ?? LooseFluentDescriptor::withoutSchema(),
                $validationRules,
                $nestedRuleset,
            );

            $baseSchema = $contextualParser->modifiedBaseSchema() ?? $baseSchema;
        }

        return $baseSchema;
    }

    private function findPropertySchema(LooseFluentDescriptor $baseSchema, string $name): LooseFluentDescriptor|null
    {
        $properties = $baseSchema->getProperties();

        if (null === $properties) {
            return null;
        }

        foreach ($properties as $property) {
            if ($property->name() === $name) {
                return $property->schema();
            }
        }

        return null;
    }

    private function isRequired(array $validationRules): bool
    {
        $hasSometimes = false;
        $hasRequired = false;

        foreach ($validationRules as $validationRule) {
            if (!$validationRule->isString()) {
                continue;
            }

            if ('sometimes' === $validationRule->rule) {
                $hasSometimes = true;
            }
            if ('required' === $validationRule->rule) {
                $hasRequired = true;
            }
        }

        return $hasRequired && !$hasSometimes;
    }

    private function resolveParser(string $parserClass): RuleParser
    {
        if (Parsers\CustomRuleSchemaParser::class === $parserClass) {
            return new Parsers\CustomRuleSchemaParser($this->customRuleSchemas);
        }

        return app($parserClass);
    }
}
