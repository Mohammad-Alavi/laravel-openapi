<?php

namespace MohammadAlavi\LaravelRulesToSchema\Parsers;

use Illuminate\Validation\Rules\Password;
use MohammadAlavi\LaravelRulesToSchema\Contracts\RuleParser;
use MohammadAlavi\LaravelRulesToSchema\NestedRuleset;
use MohammadAlavi\LaravelRulesToSchema\ParseResult;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Type;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\LooseFluentDescriptor;

final readonly class PasswordParser implements RuleParser
{
    public function __invoke(
        string $attribute,
        LooseFluentDescriptor $schema,
        array $validationRules,
        NestedRuleset $nestedRuleset,
    ): ParseResult {
        foreach ($validationRules as $validationRule) {
            if ($validationRule->rule instanceof Password) {
                $rules = $validationRule->rule->appliedRules();

                if (filled($rules['min'])) {
                    $schema = $schema->minLength($rules['min']);
                }
                if (filled($rules['max'])) {
                    $schema = $schema->maxLength($rules['max']);
                }

                $lookaheads = [];
                if ($rules['mixedCase']) {
                    $lookaheads[] = '(?=.*\p{Ll})(?=.*\p{Lu})';
                }
                if ($rules['letters']) {
                    $lookaheads[] = '(?=.*\p{L})';
                }
                if ($rules['symbols']) {
                    $lookaheads[] = '(?=.*[\p{Z}\p{S}\p{P}])';
                }
                if ($rules['numbers']) {
                    $lookaheads[] = '(?=.*\p{N})';
                }

                $schema = $schema->type(Type::string())->pattern('/^' . implode($lookaheads) . '.*$/u');
            }
        }

        return ParseResult::single($schema);
    }
}
