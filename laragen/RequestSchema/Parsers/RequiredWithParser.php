<?php

declare(strict_types=1);

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

        $lastAttribute = array_key_last($this->allRules);
        if ($lastAttribute === $attribute) {
            $conditions = [];
            foreach ($hasRequiredWith as $attr => $args) {
                if (1 === count($args)) {
                    $ifSchema = FluentSchema::make();
                    $ifSchema->getSchemaDTO()->required($args);
                } else {
                    $anyOfConditions = [];
                    foreach ($args as $arg) {
                        $argSchema = FluentSchema::make();
                        $argSchema->getSchemaDTO()->required([$arg]);
                        $anyOfConditions[] = $argSchema;
                    }
                    $ifSchema = FluentSchema::make();
                    $ifSchema->anyOf($anyOfConditions);
                }

                $thenSchema = FluentSchema::make();
                $thenSchema->getSchemaDTO()->required([$attr]);

                $condition = FluentSchema::make();
                $condition->if($ifSchema)->then($thenSchema);
                $conditions[] = $condition;
            }

            $existing = $this->baseSchema->getSchemaDTO()->allOf ?? [];
            $this->baseSchema->allOf([...$existing, ...$conditions]);
        }

        return $schema;
    }
}
