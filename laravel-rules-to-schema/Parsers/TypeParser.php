<?php

declare(strict_types=1);

namespace MohammadAlavi\LaravelRulesToSchema\Parsers;

use Illuminate\Validation\Rules\Enum as EnumRule;
use Illuminate\Validation\Rules\In as InRule;
use MohammadAlavi\LaravelRulesToSchema\Contracts\RuleParser;
use MohammadAlavi\LaravelRulesToSchema\LaravelRuleType;
use MohammadAlavi\LaravelRulesToSchema\ValidationRuleNormalizer;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Type;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\LooseFluentDescriptor;
use ReflectionClass;

final class TypeParser implements RuleParser
{
    public function __invoke(
        string $attribute,
        LooseFluentDescriptor $schema,
        array $validationRules,
        array $nestedRuleset,
    ): array|LooseFluentDescriptor|null {
        $types = [];

        foreach ($validationRules as $validationRule) {
            $ruleName = $validationRule->name();

            if (in_array($ruleName, LaravelRuleType::string(), true)) {
                $types[] = Type::string();
            }
            if (in_array($ruleName, LaravelRuleType::integer(), true)) {
                $types[] = Type::integer();
            }
            if (in_array($ruleName, LaravelRuleType::number(), true)) {
                $types[] = Type::number();
            }
            if (in_array($ruleName, LaravelRuleType::boolean(), true)) {
                $types[] = Type::boolean();
            }
            if (in_array($ruleName, LaravelRuleType::array(), true)) {
                if (0 === count(array_diff_key($nestedRuleset, array_flip([ValidationRuleNormalizer::RULES_KEY])))) {
                    $types[] = Type::array();
                }
            }
            if (in_array($ruleName, LaravelRuleType::nullable(), true)) {
                $types[] = Type::null();
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

        return $schema;
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
            $values = invade($ruleName)->values; /** @phpstan-ignore property.protected */
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
        $enumType = invade($rule)->type; /** @phpstan-ignore property.protected */
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
