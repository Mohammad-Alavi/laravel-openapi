<?php

declare(strict_types=1);

namespace MohammadAlavi\Laragen\RequestSchema\Parsers;

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

        $lastAttribute = array_key_last($this->allRules);
        if ($lastAttribute === $attribute) {
            $conditions = [];
            foreach ($hasRequiredWithout as $attr => $args) {
                $notSchema = FluentSchema::make();
                $notSchema->getSchemaDTO()->required($args);

                $ifSchema = FluentSchema::make();
                $ifSchema->not($notSchema);

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
