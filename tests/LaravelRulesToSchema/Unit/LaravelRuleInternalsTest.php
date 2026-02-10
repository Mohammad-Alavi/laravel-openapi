<?php

declare(strict_types=1);

use Illuminate\Validation\Rules\Enum as EnumRule;
use Illuminate\Validation\Rules\In as InRule;
use MohammadAlavi\LaravelRulesToSchema\LaravelRuleInternals;
use Tests\Laragen\Support\Doubles\Models\StatusEnum;

describe(class_basename(LaravelRuleInternals::class), function (): void {
    it('extracts enum type from Enum rule', function (): void {
        $rule = new EnumRule(StatusEnum::class);

        expect(LaravelRuleInternals::enumType($rule))->toBe(StatusEnum::class);
    });

    it('extracts values from In rule', function (): void {
        $rule = new InRule(['foo', 'bar', 'baz']);

        expect(LaravelRuleInternals::inValues($rule))->toBe(['foo', 'bar', 'baz']);
    });
})->covers(LaravelRuleInternals::class);
