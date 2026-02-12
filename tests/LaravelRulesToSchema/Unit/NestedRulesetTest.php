<?php

use MohammadAlavi\LaravelRulesToSchema\NestedRuleset;
use MohammadAlavi\LaravelRulesToSchema\ValidationRule;

describe(class_basename(NestedRuleset::class), function (): void {
    it('creates with defaults', function (): void {
        $ruleset = new NestedRuleset();

        expect($ruleset->validationRules)->toBe([])
            ->and($ruleset->children)->toBe([])
            ->and($ruleset->hasChildren())->toBeFalse()
            ->and($ruleset->hasWildcardChild())->toBeFalse()
            ->and($ruleset->wildcardChild())->toBeNull();
    });

    it('creates with validation rules', function (): void {
        $rules = [new ValidationRule('required'), new ValidationRule('string')];
        $ruleset = new NestedRuleset($rules);

        expect($ruleset->validationRules)->toBe($rules)
            ->and($ruleset->children)->toBe([])
            ->and($ruleset->hasChildren())->toBeFalse();
    });

    it('creates with children', function (): void {
        $child = new NestedRuleset([new ValidationRule('string')]);
        $ruleset = new NestedRuleset([], ['address' => $child]);

        expect($ruleset->hasChildren())->toBeTrue()
            ->and($ruleset->children)->toBe(['address' => $child])
            ->and($ruleset->hasWildcardChild())->toBeFalse()
            ->and($ruleset->wildcardChild())->toBeNull();
    });

    it('detects wildcard child', function (): void {
        $wildcardChild = new NestedRuleset([new ValidationRule('integer')]);
        $ruleset = new NestedRuleset([], ['*' => $wildcardChild]);

        expect($ruleset->hasWildcardChild())->toBeTrue()
            ->and($ruleset->wildcardChild())->toBe($wildcardChild)
            ->and($ruleset->hasChildren())->toBeTrue();
    });

    it('creates with both validation rules and children', function (): void {
        $rules = [new ValidationRule('required')];
        $child = new NestedRuleset([new ValidationRule('string')]);
        $ruleset = new NestedRuleset($rules, ['name' => $child]);

        expect($ruleset->validationRules)->toBe($rules)
            ->and($ruleset->hasChildren())->toBeTrue()
            ->and($ruleset->children)->toHaveKey('name');
    });
})->covers(NestedRuleset::class);
