<?php

declare(strict_types=1);

namespace MohammadAlavi\Laragen\RequestSchema\Parsers;

use FluentJsonSchema\FluentSchema;
use Illuminate\Validation\Rules\NotIn;
use LaravelRulesToSchema\Contracts\RuleParser;

final readonly class NotInParser implements RuleParser
{
    public function __invoke(
        string $attribute,
        FluentSchema $schema,
        array $validationRules,
        array $nestedRuleset,
    ): array|FluentSchema|null {
        foreach ($validationRules as $ruleArgs) {
            $rule = $ruleArgs[0];
            $args = $ruleArgs[1] ?? [];

            $values = $this->extractValues($rule, $args);

            if (null === $values) {
                continue;
            }

            $notSchema = FluentSchema::make();
            $notSchema->getSchemaDTO()->enum = $values;
            $schema->not($notSchema);

            return $schema;
        }

        return $schema;
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
