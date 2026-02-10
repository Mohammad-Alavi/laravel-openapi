<?php

declare(strict_types=1);

namespace MohammadAlavi\LaravelRulesToSchema\Parsers;

use MohammadAlavi\LaravelRulesToSchema\Contracts\HasJsonSchema;
use MohammadAlavi\LaravelRulesToSchema\Contracts\RuleParser;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\LooseFluentDescriptor;

final readonly class CustomRuleSchemaParser implements RuleParser
{
    /** @param array<string, mixed> $customRuleSchemas */
    public function __construct(
        private array $customRuleSchemas = [],
    ) {
    }

    public function __invoke(
        string $attribute,
        LooseFluentDescriptor $schema,
        array $validationRules,
        array $nestedRuleset,
    ): array|LooseFluentDescriptor|null {
        foreach ($validationRules as $validationRule) {
            $ruleName = $validationRule->name();

            if ($validationRule->rule instanceof HasJsonSchema) {
                return $validationRule->rule->toJsonSchema($attribute);
            }

            if (array_key_exists($ruleName, $this->customRuleSchemas)) {
                $typehint = $this->customRuleSchemas[$ruleName];

                if (is_string($typehint)) {
                    if (class_exists($typehint)) {
                        $instance = app($typehint);

                        if (!$instance instanceof HasJsonSchema) {
                            throw new \RuntimeException('Custom rule schemas must implement ' . HasJsonSchema::class);
                        }

                        return $instance->toJsonSchema($attribute);
                    }

                    $schema = $schema->type($typehint);
                } elseif (is_array($typehint)) {
                    $types = array_map(
                        static fn (mixed $type): string => is_object($type) && method_exists($type, 'value') ? $type->value : (string) $type,
                        $typehint,
                    );
                    $schema = $schema->type(...$types);
                }
            }
        }

        return $schema;
    }
}
