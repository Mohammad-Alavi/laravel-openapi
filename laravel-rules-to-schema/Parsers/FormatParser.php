<?php

declare(strict_types=1);

namespace MohammadAlavi\LaravelRulesToSchema\Parsers;

use MohammadAlavi\LaravelRulesToSchema\Contracts\RuleParser;
use MohammadAlavi\LaravelRulesToSchema\NestedRuleset;
use MohammadAlavi\LaravelRulesToSchema\ParseResult;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Formats\StringFormat;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\LooseFluentDescriptor;

final readonly class FormatParser implements RuleParser
{
    public function __invoke(
        string $attribute,
        LooseFluentDescriptor $schema,
        array $validationRules,
        NestedRuleset $nestedRuleset,
    ): ParseResult {
        foreach ($validationRules as $validationRule) {
            $schema = match ($validationRule->rule) {
                'uuid' => $schema->format(StringFormat::UUID),
                'url' => $schema->format(StringFormat::URI),
                'ipv4' => $schema->format(StringFormat::IPV4),
                'ipv6' => $schema->format(StringFormat::IPV6),
                'email' => $schema->format(StringFormat::EMAIL),
                default => $schema,
            };

            if ('mimetypes' === $validationRule->rule && count($validationRule->args) > 0) {
                $schema = $schema->contentMediaType($validationRule->args[0]);
            }
        }

        return ParseResult::single($schema);
    }
}
