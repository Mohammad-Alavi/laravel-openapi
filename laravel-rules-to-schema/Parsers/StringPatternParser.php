<?php

declare(strict_types=1);

namespace MohammadAlavi\LaravelRulesToSchema\Parsers;

use MohammadAlavi\LaravelRulesToSchema\Contracts\RuleParser;
use MohammadAlavi\LaravelRulesToSchema\NestedRuleset;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Type;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\LooseFluentDescriptor;

final readonly class StringPatternParser implements RuleParser
{
    private const PATTERN_RULES = [
        'starts_with',
        'ends_with',
        'doesnt_start_with',
        'doesnt_end_with',
        'lowercase',
        'uppercase',
        'ascii',
        'hex_color',
    ];

    public function __invoke(
        string $attribute,
        LooseFluentDescriptor $schema,
        array $validationRules,
        NestedRuleset $nestedRuleset,
    ): array|LooseFluentDescriptor|null {
        foreach ($validationRules as $validationRule) {
            if (!$validationRule->isString() || !in_array($validationRule->rule, self::PATTERN_RULES, true)) {
                continue;
            }

            $pattern = $this->buildPattern($validationRule->rule, $validationRule->args);

            if (null !== $pattern) {
                $schema = $schema->type(Type::string())->pattern($pattern);
            }
        }

        return $schema;
    }

    private function buildPattern(string $rule, array $args): string|null
    {
        return match ($rule) {
            'starts_with' => '^(' . $this->escapeAndJoin($args) . ')',
            'ends_with' => '(' . $this->escapeAndJoin($args) . ')$',
            'doesnt_start_with' => '^(?!(' . $this->escapeAndJoin($args) . '))',
            'doesnt_end_with' => '(?!.*(' . $this->escapeAndJoin($args) . ')$)',
            'lowercase' => '^[^A-Z]*$',
            'uppercase' => '^[^a-z]*$',
            'ascii' => '^[\x20-\x7E]*$',
            'hex_color' => '^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$',
            default => null,
        };
    }

    private function escapeAndJoin(array $values): string
    {
        return implode('|', array_map(
            static fn (string $value): string => preg_quote($value, '/'),
            $values,
        ));
    }
}
