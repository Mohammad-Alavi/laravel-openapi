<?php

namespace MohammadAlavi\Laragen\RuleParsers;

use FluentJsonSchema\FluentSchema;
use Illuminate\Support\Arr;
use LaravelRulesToSchema\Contracts\RuleParser;

final readonly class RequiredWith implements RuleParser
{
    public function __invoke(string $attribute, FluentSchema $schema, array $validationRules, array $nestedRuleset): array|FluentSchema|null
    {
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
                    $hasRequiredWith[$attr] = true;
                }
            }
        }

        if (empty($hasRequiredWith)) {
            return $schema;
        }

        if (!$this->allAttributesHaveRequireWithRule($hasRequiredWith, $allRules)) {
            $shouldWrapInAllOf = true;
        }

        foreach ($validationRules as $ruleArgs) {
            [$rule, $args] = $ruleArgs;

            if (!is_string($rule)) {
                continue;
            }

            if ('required_with' === $rule) {
                $anyOf = [
                    'properties' => [
                        $attribute => $schema->object(),
                    ],
                    'required' => [$attribute],
                ];

                if (!$shouldWrapInAllOf) {
                    $baseSchema->anyOf(
                        [
                            ...($baseSchema->getSchemaDTO()->anyOf ?? []),
                            $anyOf,
                        ],
                    );

                    return null;
                }

                $allOf = $baseSchema->getSchemaDTO()->allOf ?? [];
                $allOf['anyOf'] = [
                    ...($baseSchema->getSchemaDTO()->allOf['anyOf'] ?? []),
                    $anyOf,
                ];
                $baseSchema->allOf(Arr::sort($allOf, static fn ($value, $key) => 'anyOf' !== $key));

                return null;
            }

            if ($shouldWrapInAllOf) {
                $allOf = [
                    ...($baseSchema->getSchemaDTO()->allOf ?? []),
                    ['properties' => [$attribute => $schema->object()]],
                ];
                $baseSchema->allOf($allOf);

                return null;
            }
        }

        return $schema;
    }

    private function allAttributesHaveRequireWithRule(array $hasRequiredWith, array $allRules): bool
    {
        return count($allRules) === count($hasRequiredWith);
    }
}
