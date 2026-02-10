<?php

declare(strict_types=1);

namespace MohammadAlavi\LaravelRulesToSchema\Parsers;

use MohammadAlavi\LaravelRulesToSchema\Contracts\HasJsonSchema;
use MohammadAlavi\LaravelRulesToSchema\Contracts\RuleParser;
use MohammadAlavi\LaravelRulesToSchema\CustomRuleSchemaMapping;
use MohammadAlavi\LaravelRulesToSchema\NestedRuleset;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\LooseFluentDescriptor;

final readonly class CustomRuleSchemaParser implements RuleParser
{
    /** @param array<string, CustomRuleSchemaMapping> $customRuleSchemas */
    public function __construct(
        private array $customRuleSchemas = [],
    ) {
    }

    public function __invoke(
        string $attribute,
        LooseFluentDescriptor $schema,
        array $validationRules,
        NestedRuleset $nestedRuleset,
    ): array|LooseFluentDescriptor|null {
        foreach ($validationRules as $validationRule) {
            if ($validationRule->rule instanceof HasJsonSchema) {
                return $validationRule->rule->toJsonSchema($attribute);
            }

            $ruleName = $validationRule->name();

            if (array_key_exists($ruleName, $this->customRuleSchemas)) {
                return $this->customRuleSchemas[$ruleName]->apply($attribute, $schema);
            }
        }

        return $schema;
    }
}
