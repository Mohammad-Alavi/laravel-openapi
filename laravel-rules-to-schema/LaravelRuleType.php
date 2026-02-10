<?php

declare(strict_types=1);

namespace MohammadAlavi\LaravelRulesToSchema;

final class LaravelRuleType
{
    /** @return string[] */
    public static function string(): array
    {
        return [
            'string',
            'password',
            'date',
            'date_format',
            'date_equals',
            'alpha',
            'alpha_dash',
            'alpha_num',
            'ip',
            'ipv4',
            'ipv6',
            'mac_address',
            'json',
            'url',
            'uuid',
            'ulid',
            'regex',
            'not_regex',
            'email',
        ];
    }

    /** @return string[] */
    public static function integer(): array
    {
        return [
            'integer',
            'int',
            'digits',
            'digits_between',
        ];
    }

    /** @return string[] */
    public static function number(): array
    {
        return [
            'numeric',
            'decimal',
        ];
    }

    /** @return string[] */
    public static function boolean(): array
    {
        return [
            'bool',
            'boolean',
        ];
    }

    /** @return string[] */
    public static function array(): array
    {
        return [
            'array',
            'list',
        ];
    }

    /** @return string[] */
    public static function nullable(): array
    {
        return [
            'nullable',
        ];
    }

    /** @return string[] */
    public static function conditional(): array
    {
        return [
            'sometimes',
            'required_if',
            'required_unless',
            'required_with',
            'required_without',
            'exclude_if',
            'exclude_unless',
            'exclude_with',
            'exclude_without',
            'missing_if',
            'missing_unless',
            'missing_with',
            'missing_without',
            'prohibited_if',
            'prohibited_unless',
            'prohibited_with',
            'prohibited_without',
            \Illuminate\Validation\Rules\RequiredIf::class,
            \Illuminate\Validation\Rules\ProhibitedIf::class,
            \Illuminate\Validation\Rules\ExcludeIf::class,
        ];
    }

    /** @return string[] */
    public static function exclude(): array
    {
        return [
            'prohibited',
            'missing',
            'exclude',
        ];
    }
}
