<?php

declare(strict_types=1);

namespace MohammadAlavi\Laragen\RequestSchema\Parsers;

use FluentJsonSchema\Enums\JsonSchemaType;
use FluentJsonSchema\FluentSchema;
use LaravelRulesToSchema\Contracts\RuleParser;

final readonly class ComparisonConstraintParser implements RuleParser
{
    public function __invoke(
        string $attribute,
        FluentSchema $schema,
        array $validationRules,
        array $nestedRuleset,
    ): array|FluentSchema|null {
        foreach ($validationRules as $ruleArgs) {
            [$rule, $args] = $ruleArgs;

            if (!is_string($rule)) {
                continue;
            }

            if ('between' === $rule && count($args) >= 2) {
                $this->applyRange($schema, (int) $args[0], (int) $args[1]);
            }

            if ('size' === $rule && count($args) >= 1) {
                $value = (int) $args[0];
                $this->applyRange($schema, $value, $value);
            }
        }

        return $schema;
    }

    private function applyRange(FluentSchema $schema, int $min, int $max): void
    {
        $type = $this->resolveType($schema);

        match ($type) {
            'number' => $schema->number()->minimum($min)->maximum($max),
            'array' => $schema->array()->minItems($min)->maxItems($max),
            default => $schema->string()->minLength($min)->maxLength($max),
        };
    }

    private function resolveType(FluentSchema $schema): string
    {
        $schemaTypes = $schema->getSchemaDTO()->type;

        foreach ($schemaTypes ?? [] as $type) {
            if (in_array($type, [JsonSchemaType::INTEGER, JsonSchemaType::NUMBER])) {
                return 'number';
            }
            if (JsonSchemaType::ARRAY === $type) {
                return 'array';
            }
        }

        return 'string';
    }
}
