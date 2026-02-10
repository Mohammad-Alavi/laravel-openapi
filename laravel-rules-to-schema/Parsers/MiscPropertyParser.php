<?php

declare(strict_types=1);

namespace MohammadAlavi\LaravelRulesToSchema\Parsers;

use MohammadAlavi\LaravelRulesToSchema\Contracts\RuleParser;
use MohammadAlavi\LaravelRulesToSchema\NestedRuleset;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\LooseFluentDescriptor;

final readonly class MiscPropertyParser implements RuleParser
{
    public function __invoke(
        string $attribute,
        LooseFluentDescriptor $schema,
        array $validationRules,
        NestedRuleset $nestedRuleset,
    ): array|LooseFluentDescriptor|null {
        $schemaType = $schema->getType();
        $types = is_array($schemaType) ? $schemaType : ($schemaType ? [$schemaType] : []);

        foreach ($validationRules as $validationRule) {
            foreach ($types as $type) {
                if ('string' === $type) {
                    if ('min' === $validationRule->rule && count($validationRule->args) > 0) {
                        $schema = $schema->minLength((int) $validationRule->args[0]);
                    } elseif ('max' === $validationRule->rule && count($validationRule->args) > 0) {
                        $schema = $schema->maxLength((int) $validationRule->args[0]);
                    }
                } elseif (in_array($type, ['integer', 'number'], true)) {
                    if ('min' === $validationRule->rule && count($validationRule->args) > 0) {
                        $schema = $schema->minimum((float) $validationRule->args[0]);
                    } elseif ('max' === $validationRule->rule && count($validationRule->args) > 0) {
                        $schema = $schema->maximum((float) $validationRule->args[0]);
                    }
                } elseif ('array' === $type) {
                    if ('min' === $validationRule->rule && count($validationRule->args) > 0) {
                        $schema = $schema->minItems((int) $validationRule->args[0]);
                    } elseif ('max' === $validationRule->rule && count($validationRule->args) > 0) {
                        $schema = $schema->maxItems((int) $validationRule->args[0]);
                    }
                }
            }

            if ('regex' === $validationRule->rule && count($validationRule->args) > 0) {
                $matched = preg_match('/^(.)(.*?)\1[a-zA-Z]*$/', $validationRule->args[0], $matches);

                if ($matched) {
                    $schema = $schema->pattern($matches[2]);
                }
            }
        }

        return $schema;
    }
}
