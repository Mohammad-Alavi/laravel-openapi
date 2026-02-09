<?php

declare(strict_types=1);

namespace MohammadAlavi\Laragen\RequestSchema\Parsers;

use FluentJsonSchema\FluentSchema;

final class ConditionalAcceptedParser implements ContextAwareRuleParser
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

        foreach ($validationRules as $ruleArgs) {
            [$rule, $args] = $ruleArgs;

            if (!is_string($rule)) {
                continue;
            }

            match ($rule) {
                'accepted_if' => $this->applyConditional($schema, $args, true),
                'declined_if' => $this->applyConditional($schema, $args, false),
                default => null,
            };
        }

        return $schema;
    }

    private function applyConditional(FluentSchema $schema, array $args, bool $acceptedValue): void
    {
        $ifSchema = FluentSchema::make();
        $fieldSchema = FluentSchema::make();
        $fieldSchema->getSchemaDTO()->const = $args[1] ?? null;
        $ifSchema->getSchemaDTO()->properties([$args[0] => $fieldSchema]);

        $thenSchema = FluentSchema::make();
        $thenSchema->type()->boolean();
        $thenSchema->getSchemaDTO()->const = $acceptedValue;

        $schema->if($ifSchema)->then($thenSchema);
    }
}
