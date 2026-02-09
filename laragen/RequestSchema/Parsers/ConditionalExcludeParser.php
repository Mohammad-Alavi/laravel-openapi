<?php

declare(strict_types=1);

namespace MohammadAlavi\Laragen\RequestSchema\Parsers;

use FluentJsonSchema\FluentSchema;

final class ConditionalExcludeParser implements ContextAwareRuleParser
{
    private FluentSchema|null $baseSchema = null;

    /** @var array<string, mixed>|null */
    private array|null $allRules = null;

    private const VALUE_CONDITION_RULES = [
        'exclude_if' => 'then',
        'exclude_unless' => 'else',
        'missing_if' => 'then',
        'missing_unless' => 'else',
    ];

    private const PRESENCE_CONDITION_RULES = [
        'exclude_with' => 'then',
        'exclude_without' => 'then',
        'missing_with' => 'then',
        'missing_with_all' => 'then',
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

            if (!is_string($rule)) {
                continue;
            }

            if (array_key_exists($rule, self::VALUE_CONDITION_RULES)) {
                $this->applyValueCondition($schema, $attribute, $rule, $args);
            } elseif (array_key_exists($rule, self::PRESENCE_CONDITION_RULES)) {
                $this->applyPresenceCondition($schema, $attribute, $rule, $args);
            }
        }

        return $schema;
    }

    private function applyValueCondition(FluentSchema $schema, string $attribute, string $rule, array $args): void
    {
        $branch = self::VALUE_CONDITION_RULES[$rule];

        $ifSchema = FluentSchema::make();
        $fieldSchema = FluentSchema::make();
        $fieldSchema->getSchemaDTO()->const = $args[1] ?? null;
        $ifSchema->getSchemaDTO()->properties([$args[0] => $fieldSchema]);

        $excludeSchema = $this->buildExcludeSchema($attribute);

        $schema->if($ifSchema);

        if ('then' === $branch) {
            $schema->then($excludeSchema);
        } else {
            $schema->else($excludeSchema);
        }
    }

    private function applyPresenceCondition(FluentSchema $schema, string $attribute, string $rule, array $args): void
    {
        $excludeSchema = $this->buildExcludeSchema($attribute);

        if ('exclude_without' === $rule) {
            $requiredSchema = FluentSchema::make();
            $requiredSchema->getSchemaDTO()->required($args);
            $notRequiredSchema = FluentSchema::make();
            $notRequiredSchema->not($requiredSchema);

            $schema->if($notRequiredSchema)->then($excludeSchema);

            return;
        }

        $ifSchema = FluentSchema::make();
        $ifSchema->getSchemaDTO()->required($args);

        $schema->if($ifSchema)->then($excludeSchema);
    }

    private function buildExcludeSchema(string $attribute): FluentSchema
    {
        $propertySchema = FluentSchema::make();
        $propertySchema->getSchemaDTO()->const = $attribute;

        $excludeSchema = FluentSchema::make();
        $excludeSchema->not(FluentSchema::make()->true());

        return $excludeSchema;
    }
}
