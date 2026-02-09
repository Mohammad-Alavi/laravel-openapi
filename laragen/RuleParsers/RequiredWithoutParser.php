<?php

namespace MohammadAlavi\Laragen\RuleParsers;

use FluentJsonSchema\FluentSchema;

final class RequiredWithoutParser implements ContextAwareRuleParser
{
    private FluentSchema|null $baseSchema = null;

    /** @var array<string, mixed>|null */
    private array|null $allRules = null;

    public function withContext(FluentSchema $baseSchema, array $allRules, string|null $request): static
    {
        $clone = clone $this;
        $clone->baseSchema = $baseSchema;
        $clone->allRules = $allRules;

        return $clone;
    }

    public function __invoke(
        string $attribute,
        FluentSchema $schema,
        array $validationRules,
        array $nestedRuleset,
    ): array|FluentSchema|null {
        if (null === $this->baseSchema || null === $this->allRules) {
            return $schema;
        }

        $shouldWrapInAllOf = false;
        $hasRequiredWithout = [];
        foreach ($this->allRules as $attr => $ruleSet) {
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

        if (!$this->allAttributesHaveRequireWithRule($hasRequiredWithout, $this->allRules)) {
            $shouldWrapInAllOf = true;
        }

        $lastAttribute = array_key_last($this->allRules);
        if ($lastAttribute === $attribute) {
            $properties = $this->baseSchema->getSchemaDTO()?->properties ?? [];
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

            $this->baseSchema->getSchemaDTO()->properties = null;
            if ($shouldWrapInAllOf) {
                $processedAllOf = [];
                foreach ($allOf as $prop => $propSchema) {
                    $processedAllOf['properties'][$prop] = $propSchema;
                }
                if (!is_null($this->baseSchema->getSchemaDTO()->required) && [] !== $this->baseSchema->getSchemaDTO()->required) {
                    $processedAllOf['required'] = $this->baseSchema->getSchemaDTO()->required;
                }
                $this->baseSchema->getSchemaDTO()->required = null;
                $this->baseSchema->allOf([
                    ['oneOf' => $processedOneOf],
                    $processedAllOf,
                ]);
            } else {
                $this->baseSchema->oneOf($processedOneOf);
            }
        }

        return $schema;
    }

    private function allAttributesHaveRequireWithRule(array $hasRequiredWithout, array $allRules): bool
    {
        return count($allRules) === count($hasRequiredWithout);
    }
}
