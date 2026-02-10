<?php

declare(strict_types=1);

namespace MohammadAlavi\LaravelRulesToSchema\Parsers;

use MohammadAlavi\LaravelRulesToSchema\Contracts\RuleParser;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Type;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\LooseFluentDescriptor;

final readonly class FileUploadParser implements RuleParser
{
    private const FILE_RULES = ['file', 'image', 'mimes', 'mimetypes'];

    public function __invoke(
        string $attribute,
        LooseFluentDescriptor $schema,
        array $validationRules,
        array $nestedRuleset,
    ): array|LooseFluentDescriptor|null {
        foreach ($validationRules as $validationRule) {
            if ($validationRule->isString() && in_array($validationRule->rule, self::FILE_RULES, true)) {
                return $schema->type(Type::string())->format('binary');
            }
        }

        return $schema;
    }
}
