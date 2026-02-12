<?php

use MohammadAlavi\LaravelRulesToSchema\ValidationRule;

describe(class_basename(ValidationRule::class), function (): void {
    it('creates with string rule and no args', function (): void {
        $rule = new ValidationRule('required');

        expect($rule->rule)->toBe('required')
            ->and($rule->args)->toBe([])
            ->and($rule->name())->toBe('required')
            ->and($rule->isString())->toBeTrue()
            ->and($rule->isObject())->toBeFalse()
            ->and($rule->hasArgs())->toBeFalse();
    });

    it('creates with string rule and args', function (): void {
        $rule = new ValidationRule('min', ['3']);

        expect($rule->rule)->toBe('min')
            ->and($rule->args)->toBe(['3'])
            ->and($rule->arg(0))->toBe('3')
            ->and($rule->hasArgs())->toBeTrue();
    });

    it('creates with object rule', function (): void {
        $object = new stdClass();
        $rule = new ValidationRule($object);

        expect($rule->rule)->toBe($object)
            ->and($rule->name())->toBe(stdClass::class)
            ->and($rule->isString())->toBeFalse()
            ->and($rule->isObject())->toBeTrue();
    });

    it('returns default when arg index does not exist', function (): void {
        $rule = new ValidationRule('required');

        expect($rule->arg(0))->toBeNull()
            ->and($rule->arg(1, 'fallback'))->toBe('fallback');
    });

    it('returns arg at specific index', function (): void {
        $rule = new ValidationRule('between', ['1', '100']);

        expect($rule->arg(0))->toBe('1')
            ->and($rule->arg(1))->toBe('100')
            ->and($rule->arg(2))->toBeNull();
    });
})->covers(ValidationRule::class);
