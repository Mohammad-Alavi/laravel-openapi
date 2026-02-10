<?php

declare(strict_types=1);

namespace MohammadAlavi\LaravelRulesToSchema\Parsers;

use MohammadAlavi\LaravelRulesToSchema\Contracts\HasDocs;
use MohammadAlavi\LaravelRulesToSchema\Contracts\RuleParser;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\LooseFluentDescriptor;

final readonly class CustomRuleDocsParser implements RuleParser
{
    public function __invoke(
        string $attribute,
        LooseFluentDescriptor $schema,
        array $validationRules,
        array $nestedRuleset,
    ): array|LooseFluentDescriptor|null {
        foreach ($validationRules as $validationRule) {
            if (!$validationRule->rule instanceof HasDocs) {
                continue;
            }

            $docs = $validationRule->rule->docs();

            if ($docs->hasType()) {
                $schema = $schema->type($docs->type);
            }

            if ($docs->hasFormat()) {
                $schema = $schema->format($docs->format);
            }

            if ($docs->hasDescription()) {
                $schema = $schema->description($docs->description);
            }

            if ($docs->hasEnum()) {
                $schema = $schema->enum(...$docs->enum);
            }
        }

        return $schema;
    }
}
