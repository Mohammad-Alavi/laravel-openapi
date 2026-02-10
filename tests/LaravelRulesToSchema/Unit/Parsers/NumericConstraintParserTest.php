<?php

declare(strict_types=1);

use MohammadAlavi\LaravelRulesToSchema\NestedRuleset;
use MohammadAlavi\LaravelRulesToSchema\Parsers\NumericConstraintParser;
use MohammadAlavi\LaravelRulesToSchema\ValidationRule;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\LooseFluentDescriptor;

describe(class_basename(NumericConstraintParser::class), function (): void {
    it('sets multipleOf for multiple_of rule', function (): void {
        $parser = new NumericConstraintParser();
        $schema = LooseFluentDescriptor::withoutSchema();

        $result = $parser('field', $schema, [new ValidationRule('multiple_of', ['3'])], new NestedRuleset());

        $compiled = $result->schema()->compile();

        expect($compiled['multipleOf'])->toBe(3);
    });

    it('sets maximum for max_digits rule', function (): void {
        $parser = new NumericConstraintParser();
        $schema = LooseFluentDescriptor::withoutSchema();

        $result = $parser('field', $schema, [new ValidationRule('max_digits', ['5'])], new NestedRuleset());

        $compiled = $result->schema()->compile();

        expect($compiled['maximum'])->toBe(99999);
    });

    it('sets minimum for min_digits rule', function (): void {
        $parser = new NumericConstraintParser();
        $schema = LooseFluentDescriptor::withoutSchema();

        $result = $parser('field', $schema, [new ValidationRule('min_digits', ['3'])], new NestedRuleset());

        $compiled = $result->schema()->compile();

        expect($compiled['minimum'])->toBe(100);
    });

    it('sets minimum to 0 for min_digits with 1 digit', function (): void {
        $parser = new NumericConstraintParser();
        $schema = LooseFluentDescriptor::withoutSchema();

        $result = $parser('field', $schema, [new ValidationRule('min_digits', ['1'])], new NestedRuleset());

        $compiled = $result->schema()->compile();

        expect($compiled['minimum'])->toBe(0);
    });

    it('handles max_digits with 1 digit', function (): void {
        $parser = new NumericConstraintParser();
        $schema = LooseFluentDescriptor::withoutSchema();

        $result = $parser('field', $schema, [new ValidationRule('max_digits', ['1'])], new NestedRuleset());

        $compiled = $result->schema()->compile();

        expect($compiled['maximum'])->toBe(9);
    });

    it('does not modify schema for non-numeric rules', function (): void {
        $parser = new NumericConstraintParser();
        $schema = LooseFluentDescriptor::withoutSchema();

        $result = $parser('field', $schema, [new ValidationRule('required'), new ValidationRule('string')], new NestedRuleset());

        $compiled = $result->schema()->compile();

        expect($compiled)->not->toHaveKey('multipleOf')
            ->and($compiled)->not->toHaveKey('minimum')
            ->and($compiled)->not->toHaveKey('maximum');
    });

    it('handles multiple numeric rules together', function (): void {
        $parser = new NumericConstraintParser();
        $schema = LooseFluentDescriptor::withoutSchema();

        $result = $parser('field', $schema, [
            new ValidationRule('min_digits', ['2']),
            new ValidationRule('max_digits', ['4']),
            new ValidationRule('multiple_of', ['5']),
        ], new NestedRuleset());

        $compiled = $result->schema()->compile();

        expect($compiled['minimum'])->toBe(10)
            ->and($compiled['maximum'])->toBe(9999)
            ->and($compiled['multipleOf'])->toBe(5);
    });
})->covers(NumericConstraintParser::class);
