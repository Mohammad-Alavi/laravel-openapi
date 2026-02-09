<?php

declare(strict_types=1);

namespace MohammadAlavi\Laragen\RequestSchema\Parsers;

use FluentJsonSchema\FluentSchema;
use LaravelRulesToSchema\Contracts\RuleParser;

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
        FluentSchema $schema,
        array $validationRules,
        array $nestedRuleset,
    ): array|FluentSchema|null {
        foreach ($validationRules as $ruleArgs) {
            [$rule, $args] = $ruleArgs;

            if (!is_string($rule) || !in_array($rule, self::PATTERN_RULES, true)) {
                continue;
            }

            $pattern = $this->buildPattern($rule, $args);

            if (null !== $pattern) {
                $schema->type()->string()->pattern($pattern);
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
