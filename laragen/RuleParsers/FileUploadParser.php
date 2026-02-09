<?php

declare(strict_types=1);

namespace MohammadAlavi\Laragen\RuleParsers;

use FluentJsonSchema\FluentSchema;
use LaravelRulesToSchema\Contracts\RuleParser;

final readonly class FileUploadParser implements RuleParser
{
    private const FILE_RULES = ['file', 'image', 'mimes', 'mimetypes'];

    public function __invoke(
        string $attribute,
        FluentSchema $schema,
        array $validationRules,
        array $nestedRuleset,
    ): array|FluentSchema|null {
        foreach ($validationRules as $ruleArgs) {
            [$rule] = $ruleArgs;

            if (is_string($rule) && in_array($rule, self::FILE_RULES, true)) {
                $schema->type()->string();
                $schema->format()->custom('binary');

                return $schema;
            }
        }

        return $schema;
    }
}
