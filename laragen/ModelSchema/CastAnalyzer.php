<?php

namespace MohammadAlavi\Laragen\ModelSchema;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\JSONSchema;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Formats\StringFormat;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;

final readonly class CastAnalyzer
{
    public static function resolve(string $cast): JSONSchema
    {
        // Handle decimal:N format
        $baseCast = str_contains($cast, ':') ? explode(':', $cast)[0] : $cast;

        return match ($baseCast) {
            'int', 'integer' => Schema::integer(),
            'float', 'double', 'real' => Schema::number(),
            'string' => Schema::string(),
            'bool', 'boolean' => Schema::boolean(),
            'array', 'collection', 'object' => Schema::object(),
            'date', 'datetime', 'immutable_date', 'immutable_datetime' => Schema::string()->format(StringFormat::DATE_TIME),
            'timestamp' => Schema::integer(),
            'decimal' => Schema::string(),
            default => self::resolveClassCast($cast),
        };
    }

    private static function resolveClassCast(string $cast): JSONSchema
    {
        if (enum_exists($cast) && is_subclass_of($cast, \BackedEnum::class)) {
            /** @var \BackedEnum[] $cases */
            $cases = $cast::cases();

            return Schema::enum(...array_map(
                static fn (\BackedEnum $case): string|int => $case->value,
                $cases,
            ));
        }

        return Schema::string();
    }
}
