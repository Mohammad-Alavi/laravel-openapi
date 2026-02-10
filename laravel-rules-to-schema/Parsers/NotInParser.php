<?php

declare(strict_types=1);

namespace MohammadAlavi\LaravelRulesToSchema\Parsers;

use Illuminate\Validation\Rules\NotIn;
use MohammadAlavi\LaravelRulesToSchema\Contracts\RuleParser;
use MohammadAlavi\LaravelRulesToSchema\NestedRuleset;
use MohammadAlavi\LaravelRulesToSchema\ParseResult;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\LooseFluentDescriptor;

final readonly class NotInParser implements RuleParser
{
    public function __invoke(
        string $attribute,
        LooseFluentDescriptor $schema,
        array $validationRules,
        NestedRuleset $nestedRuleset,
    ): ParseResult {
        foreach ($validationRules as $validationRule) {
            $values = $this->extractValues($validationRule->rule, $validationRule->args);

            if (null === $values) {
                continue;
            }

            return ParseResult::single($schema->not(LooseFluentDescriptor::withoutSchema()->enum(...$values)));
        }

        return ParseResult::single($schema);
    }

    private function extractValues(mixed $rule, array $args): array|null
    {
        if ('not_in' === $rule) {
            return $args;
        }

        if ($rule instanceof NotIn) {
            $string = (string) $rule;
            $csv = mb_substr($string, mb_strlen('not_in:'));
            $values = str_getcsv($csv);

            return array_map(static fn (string $v): string => trim($v), $values);
        }

        return null;
    }
}
