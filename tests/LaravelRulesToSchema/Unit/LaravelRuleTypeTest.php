<?php

declare(strict_types=1);

use MohammadAlavi\LaravelRulesToSchema\LaravelRuleType;

describe(class_basename(LaravelRuleType::class), function (): void {
    it('resolves string rules to string type', function (string $rule): void {
        expect(LaravelRuleType::resolve($rule)->value())->toBe('string');
    })->with([
        'string', 'password', 'date', 'date_format', 'date_equals',
        'alpha', 'alpha_dash', 'alpha_num', 'ip', 'ipv4', 'ipv6',
        'mac_address', 'json', 'url', 'uuid', 'ulid', 'regex', 'not_regex', 'email',
    ]);

    it('resolves integer rules to integer type', function (string $rule): void {
        expect(LaravelRuleType::resolve($rule)->value())->toBe('integer');
    })->with(['integer', 'int', 'digits', 'digits_between']);

    it('resolves number rules to number type', function (string $rule): void {
        expect(LaravelRuleType::resolve($rule)->value())->toBe('number');
    })->with(['numeric', 'decimal']);

    it('resolves boolean rules to boolean type', function (string $rule): void {
        expect(LaravelRuleType::resolve($rule)->value())->toBe('boolean');
    })->with(['bool', 'boolean']);

    it('resolves array rules to array type', function (string $rule): void {
        expect(LaravelRuleType::resolve($rule)->value())->toBe('array');
    })->with(['array', 'list']);

    it('resolves nullable rule to null type', function (): void {
        expect(LaravelRuleType::resolve('nullable')->value())->toBe('null');
    });

    it('returns null for unknown rules', function (string $rule): void {
        expect(LaravelRuleType::resolve($rule))->toBeNull();
    })->with(['required', 'min', 'max', 'confirmed', 'unknown_rule']);

    it('returns null for exclude rules', function (string $rule): void {
        expect(LaravelRuleType::resolve($rule))->toBeNull();
    })->with(['prohibited', 'missing', 'exclude']);
})->covers(LaravelRuleType::class);
