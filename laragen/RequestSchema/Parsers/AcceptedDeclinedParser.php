<?php

declare(strict_types=1);

namespace MohammadAlavi\Laragen\RequestSchema\Parsers;

use FluentJsonSchema\FluentSchema;
use LaravelRulesToSchema\Contracts\RuleParser;

final readonly class AcceptedDeclinedParser implements RuleParser
{
    private const RULES = ['accepted', 'declined'];

    public function __invoke(
        string $attribute,
        FluentSchema $schema,
        array $validationRules,
        array $nestedRuleset,
    ): array|FluentSchema|null {
        foreach ($validationRules as $ruleArgs) {
            [$rule] = $ruleArgs;

            if (is_string($rule) && in_array($rule, self::RULES, true)) {
                $schema->type()->boolean();

                return $schema;
            }
        }

        return $schema;
    }
}
