<?php

declare(strict_types=1);

namespace MohammadAlavi\Laragen\RequestSchema\Parsers;

use FluentJsonSchema\Enums\JsonSchemaType;
use FluentJsonSchema\FluentSchema;
use LaravelRulesToSchema\Contracts\RuleParser;

final readonly class AdditionalConstraintParser implements RuleParser
{
    public function __invoke(
        string $attribute,
        FluentSchema $schema,
        array $validationRules,
        array $nestedRuleset,
    ): array|FluentSchema|null {
        foreach ($validationRules as $ruleArgs) {
            [$rule, $args] = $ruleArgs;

            if (!is_string($rule)) {
                continue;
            }

            match ($rule) {
                'active_url' => $schema->format()->custom('uri'),
                'timezone' => $schema->format()->custom('timezone'),
                'filled' => $this->applyFilled($schema),
                'distinct' => $schema->array()->uniqueItems(true),
                'extensions' => $schema->getSchemaDTO()->enum = $args,
                default => null,
            };
        }

        return $schema;
    }

    private function applyFilled(FluentSchema $schema): void
    {
        $schemaTypes = $schema->getSchemaDTO()->type;

        foreach ($schemaTypes ?? [] as $type) {
            if (JsonSchemaType::ARRAY === $type) {
                $schema->array()->minItems(1);

                return;
            }
        }

        $schema->string()->minLength(1);
    }
}
