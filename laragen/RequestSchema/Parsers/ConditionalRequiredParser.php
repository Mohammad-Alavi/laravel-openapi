<?php

declare(strict_types=1);

namespace MohammadAlavi\Laragen\RequestSchema\Parsers;

use FluentJsonSchema\FluentSchema;

final class ConditionalRequiredParser implements ContextAwareRuleParser
{
    private FluentSchema|null $baseSchema = null;

    /** @var array<string, mixed>|null */
    private array|null $allRules = null;

    private const RULES = [
        'required_if',
        'required_unless',
        'required_with_all',
        'required_without_all',
        'required_if_accepted',
        'required_if_declined',
    ];

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

            if (!is_string($rule) || !in_array($rule, self::RULES, true)) {
                continue;
            }

            $this->applyConditional($schema, $attribute, $rule, $args);
        }

        return $schema;
    }

    private function applyConditional(FluentSchema $schema, string $attribute, string $rule, array $args): void
    {
        $thenSchema = FluentSchema::make();
        $thenSchema->getSchemaDTO()->required([$attribute]);

        match ($rule) {
            'required_if' => $this->applyRequiredIf($schema, $thenSchema, $args),
            'required_unless' => $this->applyRequiredUnless($schema, $thenSchema, $args),
            'required_with_all' => $this->applyRequiredWithAll($schema, $thenSchema, $args),
            'required_without_all' => $this->applyRequiredWithoutAll($schema, $thenSchema, $args),
            'required_if_accepted' => $this->applyRequiredIfAccepted($schema, $thenSchema, $args),
            'required_if_declined' => $this->applyRequiredIfDeclined($schema, $thenSchema, $args),
            default => null,
        };
    }

    private function applyRequiredIf(FluentSchema $schema, FluentSchema $thenSchema, array $args): void
    {
        $ifSchema = FluentSchema::make();
        $fieldSchema = FluentSchema::make();
        $fieldSchema->getSchemaDTO()->const = $args[1] ?? null;
        $ifSchema->getSchemaDTO()->properties([$args[0] => $fieldSchema]);

        $schema->if($ifSchema)->then($thenSchema);
    }

    private function applyRequiredUnless(FluentSchema $schema, FluentSchema $thenSchema, array $args): void
    {
        $ifSchema = FluentSchema::make();
        $fieldSchema = FluentSchema::make();
        $fieldSchema->getSchemaDTO()->const = $args[1] ?? null;
        $ifSchema->getSchemaDTO()->properties([$args[0] => $fieldSchema]);

        $schema->if($ifSchema)->else($thenSchema);
    }

    private function applyRequiredWithAll(FluentSchema $schema, FluentSchema $thenSchema, array $args): void
    {
        $ifSchema = FluentSchema::make();
        $ifSchema->getSchemaDTO()->required($args);

        $schema->if($ifSchema)->then($thenSchema);
    }

    private function applyRequiredWithoutAll(FluentSchema $schema, FluentSchema $thenSchema, array $args): void
    {
        $notSchema = FluentSchema::make();
        $notSchema->getSchemaDTO()->required($args);

        $ifSchema = FluentSchema::make();
        $ifSchema->not($notSchema);

        $schema->if($ifSchema)->then($thenSchema);
    }

    private function applyRequiredIfAccepted(FluentSchema $schema, FluentSchema $thenSchema, array $args): void
    {
        $ifSchema = FluentSchema::make();
        $fieldSchema = FluentSchema::make();
        $fieldSchema->getSchemaDTO()->const = true;
        $ifSchema->getSchemaDTO()->properties([$args[0] => $fieldSchema]);

        $schema->if($ifSchema)->then($thenSchema);
    }

    private function applyRequiredIfDeclined(FluentSchema $schema, FluentSchema $thenSchema, array $args): void
    {
        $ifSchema = FluentSchema::make();
        $fieldSchema = FluentSchema::make();
        $fieldSchema->getSchemaDTO()->const = false;
        $ifSchema->getSchemaDTO()->properties([$args[0] => $fieldSchema]);

        $schema->if($ifSchema)->then($thenSchema);
    }
}
