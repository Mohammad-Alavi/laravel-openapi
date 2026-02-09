<?php

namespace MohammadAlavi\Laragen\RequestSchema\Parsers;

use FluentJsonSchema\FluentSchema;

final class RequiredWithParser implements ContextAwareRuleParser
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
        $hasRequiredWith = [];
        foreach ($this->allRules as $attr => $ruleSet) {
            foreach ($ruleSet['##_VALIDATION_RULES_##'] as $set) {
                [$rule, $args] = $set;
                if ('required_with' === $rule) {
                    $hasRequiredWith[$attr] = $args;
                }
            }
        }

        if ([] === $hasRequiredWith) {
            return $schema;
        }

        if (!$this->allAttributesHaveRequiredWithRule($hasRequiredWith, $this->allRules)) {
            $shouldWrapInAllOf = true;
        }

        $lastAttribute = array_key_last($this->allRules);
        if ($lastAttribute === $attribute) {
            $properties = $this->baseSchema->getSchemaDTO()?->properties ?? [];
            /** @var array<string, FluentSchema> $allOf */
            $allOf = array_filter(
                $properties,
                static function (FluentSchema $schema, string $property) use ($hasRequiredWith) {
                    return !array_key_exists($property, $hasRequiredWith);
                },
                ARRAY_FILTER_USE_BOTH,
            );
            /** @var array<string, FluentSchema> $oneOf */
            $oneOf = array_filter(
                $properties,
                static function (FluentSchema $schema, string $property) use ($hasRequiredWith) {
                    return array_key_exists($property, $hasRequiredWith);
                },
                ARRAY_FILTER_USE_BOTH,
            );
            $processedOneOf = [];
            foreach ($oneOf as $prop => $propSchema) {
                $processedOneOf[] = [
                    'properties' => [
                        $prop => $propSchema,
                    ],
                    'required' => $hasRequiredWith[$prop],
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
                    ['anyOf' => $processedOneOf],
                    $processedAllOf,
                ]);
            } else {
                $this->baseSchema->anyOf($processedOneOf);
            }
        }

        return $schema;
    }

    private function allAttributesHaveRequiredWithRule(array $hasRequiredWith, array $allRules): bool
    {
        return count($allRules) === count($hasRequiredWith);
    }
}
