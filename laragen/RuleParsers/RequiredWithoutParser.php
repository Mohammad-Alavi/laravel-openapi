<?php

namespace MohammadAlavi\Laragen\RuleParsers;

use FluentJsonSchema\FluentSchema;
use LaravelRulesToSchema\Contracts\RuleParser;

final class RequiredWithoutParser implements RuleParser
{
    public function __invoke(
        string $attribute,
        FluentSchema $schema,
        array $validationRules,
        array $nestedRuleset,
    ): array|FluentSchema|null {
        if (func_num_args() < 6) {
            return $schema;
        }
        $baseSchema = func_get_arg(4);
        $allRules = func_get_arg(5);
        if (!($baseSchema instanceof FluentSchema) || !is_array($allRules)) {
            return $schema;
        }

        $shouldWrapInAllOf = false;
        $hasRequiredWithout = [];
        foreach ($allRules as $attr => $ruleSet) {
            foreach ($ruleSet['##_VALIDATION_RULES_##'] as $set) {
                [$rule, $args] = $set;
                if ('required_without' === $rule) {
                    $hasRequiredWithout[$attr] = $args;
                }
            }
        }

        if ([] === $hasRequiredWithout) {
            return $schema;
        }

        if (!$this->allAttributesHaveRequireWithRule($hasRequiredWithout, $allRules)) {
            $shouldWrapInAllOf = true;
        }

        $lastAttribute = array_key_last($allRules);
        if ($lastAttribute === $attribute) {
            $properties = $baseSchema->getSchemaDTO()?->properties ?? [];
            /** @var array<string, FluentSchema> $allOf */
            $allOf = array_filter(
                $properties,
                static function (FluentSchema $schema, string $property) use ($hasRequiredWithout) {
                    return !array_key_exists($property, $hasRequiredWithout);
                },
                ARRAY_FILTER_USE_BOTH,
            );
            /** @var array<string, FluentSchema> $oneOf */
            $oneOf = array_filter(
                $properties,
                static function (FluentSchema $schema, string $property) use ($hasRequiredWithout) {
                    return array_key_exists($property, $hasRequiredWithout);
                },
                ARRAY_FILTER_USE_BOTH,
            );
            $processedOneOf = [];
            foreach ($oneOf as $prop => $propSchema) {
                $processedOneOf[] = [
                    'properties' => [
                        $prop => $propSchema,
                    ],
                    'required' => $prop,
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
                    ['oneOf' => $processedOneOf],
                    $processedAllOf,
                ]);
            } else {
                $baseSchema->oneOf($processedOneOf);
            }
        }

        return $schema;
    }

    private function allAttributesHaveRequireWithRule(array $hasRequiredWithout, array $allRules): bool
    {
        return count($allRules) === count($hasRequiredWithout);
    }
}
