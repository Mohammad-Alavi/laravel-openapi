<?php

declare(strict_types=1);

namespace MohammadAlavi\Laragen\RequestSchema\Parsers;

use FluentJsonSchema\FluentSchema;

final class PresentFieldParser implements ContextAwareRuleParser
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
                'present' => $schema->getSchemaDTO()->required([$attribute]),
                'present_if' => $this->applyPresentIf($schema, $attribute, $args),
                'present_unless' => $this->applyPresentUnless($schema, $attribute, $args),
                'present_with' => $this->applyPresentWith($schema, $attribute, $args),
                'present_with_all' => $this->applyPresentWithAll($schema, $attribute, $args),
                default => null,
            };
        }

        return $schema;
    }

    private function applyPresentIf(FluentSchema $schema, string $attribute, array $args): void
    {
        $ifSchema = FluentSchema::make();
        $fieldSchema = FluentSchema::make();
        $fieldSchema->getSchemaDTO()->const = $args[1] ?? null;
        $ifSchema->getSchemaDTO()->properties([$args[0] => $fieldSchema]);

        $thenSchema = FluentSchema::make();
        $thenSchema->getSchemaDTO()->required([$attribute]);

        $schema->if($ifSchema)->then($thenSchema);
    }

    private function applyPresentUnless(FluentSchema $schema, string $attribute, array $args): void
    {
        $ifSchema = FluentSchema::make();
        $fieldSchema = FluentSchema::make();
        $fieldSchema->getSchemaDTO()->const = $args[1] ?? null;
        $ifSchema->getSchemaDTO()->properties([$args[0] => $fieldSchema]);

        $elseSchema = FluentSchema::make();
        $elseSchema->getSchemaDTO()->required([$attribute]);

        $schema->if($ifSchema)->else($elseSchema);
    }

    private function applyPresentWith(FluentSchema $schema, string $attribute, array $args): void
    {
        $ifSchema = FluentSchema::make();
        $ifSchema->getSchemaDTO()->required($args);

        $thenSchema = FluentSchema::make();
        $thenSchema->getSchemaDTO()->required([$attribute]);

        $schema->if($ifSchema)->then($thenSchema);
    }

    private function applyPresentWithAll(FluentSchema $schema, string $attribute, array $args): void
    {
        $ifSchema = FluentSchema::make();
        $ifSchema->getSchemaDTO()->required($args);

        $thenSchema = FluentSchema::make();
        $thenSchema->getSchemaDTO()->required([$attribute]);

        $schema->if($ifSchema)->then($thenSchema);
    }
}
