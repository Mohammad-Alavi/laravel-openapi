<?php

namespace MohammadAlavi\Laragen\RuleParsers;

use FluentJsonSchema\FluentSchema;
use LaravelRulesToSchema\Contracts\RuleParser;

final class RequiredWith implements RuleParser
{
    public function __invoke(string $attribute, FluentSchema $schema, array $validationRules, array $nestedRuleset): array|FluentSchema|null
    {
        if (func_num_args() < 6) {
            return $schema;
        }
        $baseSchema = func_get_arg(4);
        $allRules = func_get_arg(5);
        if (!($baseSchema instanceof FluentSchema) || !is_array($allRules)) {
            return $schema;
        }

        $shouldWrapInAllOf = false;
        $hasRequiredWith = [];
        foreach ($allRules as $attr => $ruleSet) {
            foreach ($ruleSet['##_VALIDATION_RULES_##'] as $set) {
                [$rule, $args] = $set;
                if ('required_with' === $rule) {
                    $hasRequiredWith[$attr] = $args;
                }
            }
        }

        if (empty($hasRequiredWith)) {
            return $schema;
        }

        if (!$this->allAttributesHaveRequireWithRule($hasRequiredWith, $allRules)) {
            $shouldWrapInAllOf = true;
        }

        $lastAttribute = array_key_last($allRules);
        if ($lastAttribute === $attribute) {
            $properties = $baseSchema->getSchemaDTO()?->properties ?? [];
            /** @var array<string, FluentSchema> $allOf */
            $allOf = array_filter(
                $properties,
                static function (FluentSchema $schema, string $property) use ($hasRequiredWith) {
                    return !array_key_exists($property, $hasRequiredWith);
                },
                ARRAY_FILTER_USE_BOTH,
            );
            /** @var array<string, FluentSchema> $anyOf */
            $anyOf = array_filter(
                $properties,
                static function (FluentSchema $schema, string $property) use ($hasRequiredWith) {
                    return array_key_exists($property, $hasRequiredWith);
                },
                ARRAY_FILTER_USE_BOTH,
            );
            $processedAnyOf = [];
            foreach ($anyOf as $prop => $propSchema) {
                $processedAnyOf[] = [
                    'properties' => [
                        $prop => $propSchema,
                    ],
                    'required' => $hasRequiredWith[$prop] ?? [],
                ];
            }

            $baseSchema->getSchemaDTO()->properties = null;
            if ($shouldWrapInAllOf) {
                $processedAllOf = [];
                foreach ($allOf as $prop => $propSchema) {
                    $processedAllOf['properties'][$prop] = $propSchema;
                }
                if (!is_null($baseSchema->getSchemaDTO()->required) && [] !== $baseSchema->getSchemaDTO()->required) {
                    $processedAllOf['required'] = $baseSchema->getSchemaDTO()->required;
                }
                $baseSchema->getSchemaDTO()->required = null;
                $baseSchema->allOf([
                    ['anyOf' => $processedAnyOf],
                    $processedAllOf,
                ]);
            } else {
                $baseSchema->anyOf($processedAnyOf);
            }
        }

        return $schema;
    }

    private function allAttributesHaveRequireWithRule(array $hasRequiredWith, array $allRules): bool
    {
        return count($allRules) === count($hasRequiredWith);
    }
}
