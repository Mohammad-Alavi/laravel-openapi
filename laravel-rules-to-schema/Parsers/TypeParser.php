<?php

declare(strict_types=1);

namespace MohammadAlavi\LaravelRulesToSchema\Parsers;

use Illuminate\Validation\Rules\Enum as EnumRule;
use Illuminate\Validation\Rules\In as InRule;
use MohammadAlavi\LaravelRulesToSchema\Contracts\RuleParser;
use MohammadAlavi\LaravelRulesToSchema\LaravelRuleInternals;
use MohammadAlavi\LaravelRulesToSchema\LaravelRuleType;
use MohammadAlavi\LaravelRulesToSchema\NestedRuleset;
use MohammadAlavi\LaravelRulesToSchema\ParseResult;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Type;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\LooseFluentDescriptor;
use ReflectionClass;

final class TypeParser implements RuleParser
{
    public function __invoke(
        string $attribute,
        LooseFluentDescriptor $schema,
        array $validationRules,
        NestedRuleset $nestedRuleset,
    ): ParseResult {
        $types = [];

        foreach ($validationRules as $validationRule) {
            $resolved = LaravelRuleType::resolve($validationRule->name());

            if (null !== $resolved) {
                if ('array' === $resolved->value() && $nestedRuleset->hasChildren()) {
                    // Skip array type when nested schema handles it
                } else {
                    $types[] = $resolved;
                }
            }

            if ($validationRule->rule instanceof EnumRule) {
                $types = [...$types, ...$this->parseEnumRuleTypes($validationRule->rule)];
            }

            if ($validationRule->rule instanceof InRule || 'in' === $validationRule->rule) {
                $types = [...$types, ...$this->parseInRuleTypes($validationRule->rule, $validationRule->args)];
            }
        }

        $unique = [];
        foreach ($types as $type) {
            $unique[$type->value()] = $type;
        }
        $types = array_values($unique);

        if ([] !== $types) {
            $schema = $schema->type(...$types);
        }

        return ParseResult::single($schema);
    }

    /** @return list<Type> */
    private function parseInRuleTypes(mixed $ruleName, ?array $args): array
    {
        $values = null;

        if (is_string($ruleName)) {
            $values = array_map(static function (mixed $value) {
                if (is_numeric($value)) {
                    return ctype_digit($value) ? intval($value) : floatval($value);
                }

                return $value;
            }, $args ?? []);
        } elseif ($ruleName instanceof InRule) {
            $values = LaravelRuleInternals::inValues($ruleName);
        }

        if (!$values) {
            return [];
        }

        $isString = true;
        $isInt = true;
        $isNumeric = true;

        foreach ($values as $value) {
            if (is_string($value)) {
                $isInt = false;
                $isNumeric = false;
            }
            if (is_int($value)) {
                $isString = false;
                $isNumeric = false;
            }
            if (is_float($value)) {
                $isString = false;
                $isInt = false;
            }
        }

        $types = [];
        if ($isString) {
            $types[] = Type::string();
        }
        if ($isInt) {
            $types[] = Type::integer();
        }
        if ($isNumeric) {
            $types[] = Type::number();
        }

        return $types;
    }

    /** @return list<Type> */
    private function parseEnumRuleTypes(EnumRule $rule): array
    {
        $enumType = LaravelRuleInternals::enumType($rule);
        $reflection = new ReflectionClass($enumType);

        if (
            $reflection->implementsInterface(\BackedEnum::class)
            && count($reflection->getConstants()) > 0
        ) {
            $value = array_values($reflection->getConstants())[0]->value;

            if (is_string($value)) {
                return [Type::string()];
            }
            if (is_int($value)) {
                return [Type::integer()];
            }
        }

        return [];
    }
}
