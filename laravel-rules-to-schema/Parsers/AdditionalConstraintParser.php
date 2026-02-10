<?php

declare(strict_types=1);

namespace MohammadAlavi\LaravelRulesToSchema\Parsers;

use MohammadAlavi\LaravelRulesToSchema\Contracts\RuleParser;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Formats\StringFormat;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\LooseFluentDescriptor;

final readonly class AdditionalConstraintParser implements RuleParser
{
    public function __invoke(
        string $attribute,
        LooseFluentDescriptor $schema,
        array $validationRules,
        array $nestedRuleset,
    ): array|LooseFluentDescriptor|null {
        foreach ($validationRules as $validationRule) {
            if (!$validationRule->isString()) {
                continue;
            }

            $schema = match ($validationRule->rule) {
                'active_url' => $schema->format(StringFormat::URI),
                'timezone' => $schema->format('timezone'),
                'filled' => $this->applyFilled($schema),
                'distinct' => $schema->uniqueItems(true),
                'extensions' => $schema->enum(...$validationRule->args),
                default => $schema,
            };
        }

        return $schema;
    }

    private function applyFilled(LooseFluentDescriptor $schema): LooseFluentDescriptor
    {
        $schemaType = $schema->getType();
        $types = is_array($schemaType) ? $schemaType : ($schemaType ? [$schemaType] : []);

        foreach ($types as $type) {
            if ('array' === $type) {
                return $schema->minItems(1);
            }
        }

        return $schema->minLength(1);
    }
}
