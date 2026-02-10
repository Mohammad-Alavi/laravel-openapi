<?php

declare(strict_types=1);

namespace MohammadAlavi\LaravelRulesToSchema\Parsers;

use MohammadAlavi\LaravelRulesToSchema\Contracts\RuleParser;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\LooseFluentDescriptor;

final readonly class ComparisonConstraintParser implements RuleParser
{
    public function __invoke(
        string $attribute,
        LooseFluentDescriptor $schema,
        array $validationRules,
        array $nestedRuleset,
    ): array|LooseFluentDescriptor|null {
        foreach ($validationRules as $validationRule) {
            if (!$validationRule->isString()) {
                continue;
            }

            if ('between' === $validationRule->rule && count($validationRule->args) >= 2) {
                $schema = $this->applyRange($schema, (int) $validationRule->args[0], (int) $validationRule->args[1]);
            }

            if ('size' === $validationRule->rule && count($validationRule->args) >= 1) {
                $value = (int) $validationRule->args[0];
                $schema = $this->applyRange($schema, $value, $value);
            }
        }

        return $schema;
    }

    private function applyRange(LooseFluentDescriptor $schema, int $min, int $max): LooseFluentDescriptor
    {
        $type = $this->resolveType($schema);

        return match ($type) {
            'number' => $schema->minimum($min)->maximum($max),
            'array' => $schema->minItems($min)->maxItems($max),
            default => $schema->minLength($min)->maxLength($max),
        };
    }

    private function resolveType(LooseFluentDescriptor $schema): string
    {
        $schemaType = $schema->getType();
        $types = is_array($schemaType) ? $schemaType : ($schemaType ? [$schemaType] : []);

        foreach ($types as $type) {
            if (in_array($type, ['integer', 'number'], true)) {
                return 'number';
            }
            if ('array' === $type) {
                return 'array';
            }
        }

        return 'string';
    }
}
