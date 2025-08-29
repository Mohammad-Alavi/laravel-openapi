<?php

namespace MohammadAlavi\Laragen\RuleParsers;

use FluentJsonSchema\FluentSchema;
use Illuminate\Validation\Rules\Password;
use LaravelRulesToSchema\Contracts\RuleParser;

final readonly class PasswordParser implements RuleParser
{
    public function __invoke(
        string $attribute,
        FluentSchema $schema,
        array $validationRules,
        array $nestedRuleset,
    ): array|FluentSchema|null {
        foreach ($validationRules as $ruleArgs) {
            [$rule, $args] = $ruleArgs;

            if ($rule instanceof Password) {
                $rules = $rule->appliedRules();

                if (filled($rules['min'])) {
                    $schema->string()->minLength($rules['min']);
                }
                if (filled($rules['max'])) {
                    $schema->string()->maxLength($rules['max']);
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

                $schema->type()->string()->pattern('/^' . implode($lookaheads) . '.*$/u');
            }
        }

        return $schema;
    }
}
