<?php

declare(strict_types=1);

namespace MohammadAlavi\Laragen\RequestSchema\Parsers;

use FluentJsonSchema\FluentSchema;
use LaravelRulesToSchema\Contracts\RuleParser;

final readonly class CustomRuleDocsParser implements RuleParser
{
    public function __invoke(
        string $attribute,
        FluentSchema $schema,
        array $validationRules,
        array $nestedRuleset,
    ): array|FluentSchema|null {
        foreach ($validationRules as $ruleArgs) {
            [$rule] = $ruleArgs;

            if (!is_object($rule) || !method_exists($rule, 'docs')) {
                continue;
            }

            /** @var array{type?: string, format?: string, description?: string, enum?: string[]} $docs */
            $docs = $rule->docs();

            if (isset($docs['type'])) {
                $schema->type()->fromString($docs['type']);
            }

            if (isset($docs['format'])) {
                $schema->format()->custom($docs['format']);
            }

            if (isset($docs['description'])) {
                $schema->description($docs['description']);
            }

            if (isset($docs['enum'])) {
                $schema->getSchemaDTO()->enum = $docs['enum'];
            }
        }

        return $schema;
    }
}
