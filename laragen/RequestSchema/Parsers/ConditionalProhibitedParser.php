<?php

declare(strict_types=1);

namespace MohammadAlavi\Laragen\RequestSchema\Parsers;

use FluentJsonSchema\FluentSchema;

final class ConditionalProhibitedParser implements ContextAwareRuleParser
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
                'prohibited_if' => $this->applyProhibitedIf($schema, $attribute, $args),
                'prohibited_unless' => $this->applyProhibitedUnless($schema, $attribute, $args),
                'prohibits' => $this->applyProhibits($schema, $attribute, $args),
                default => null,
            };
        }

        return $schema;
    }

    private function applyProhibitedIf(FluentSchema $schema, string $attribute, array $args): void
    {
        $ifSchema = FluentSchema::make();
        $fieldSchema = FluentSchema::make();
        $fieldSchema->getSchemaDTO()->const = $args[1] ?? null;
        $ifSchema->getSchemaDTO()->properties([$args[0] => $fieldSchema]);

        $prohibitedSchema = FluentSchema::make();
        $prohibitedSchema->not(FluentSchema::make()->true());

        $schema->if($ifSchema)->then($prohibitedSchema);
    }

    private function applyProhibitedUnless(FluentSchema $schema, string $attribute, array $args): void
    {
        $ifSchema = FluentSchema::make();
        $fieldSchema = FluentSchema::make();
        $fieldSchema->getSchemaDTO()->const = $args[1] ?? null;
        $ifSchema->getSchemaDTO()->properties([$args[0] => $fieldSchema]);

        $prohibitedSchema = FluentSchema::make();
        $prohibitedSchema->not(FluentSchema::make()->true());

        $schema->if($ifSchema)->else($prohibitedSchema);
    }

    private function applyProhibits(FluentSchema $schema, string $attribute, array $args): void
    {
        $ifSchema = FluentSchema::make();
        $ifSchema->getSchemaDTO()->required([$attribute]);

        $prohibitedSchema = FluentSchema::make();
        $prohibitedSchema->not(FluentSchema::make()->true());

        $schema->if($ifSchema)->then($prohibitedSchema);
    }
}
