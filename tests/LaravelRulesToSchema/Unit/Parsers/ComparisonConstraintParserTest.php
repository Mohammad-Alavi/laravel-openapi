<?php

use MohammadAlavi\LaravelRulesToSchema\NestedRuleset;
use MohammadAlavi\LaravelRulesToSchema\Parsers\ComparisonConstraintParser;
use MohammadAlavi\LaravelRulesToSchema\ValidationRule;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Type;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\LooseFluentDescriptor;

describe(class_basename(ComparisonConstraintParser::class), function (): void {
    it('sets minLength and maxLength for between rule on string type', function (): void {
        $parser = new ComparisonConstraintParser();
        $schema = LooseFluentDescriptor::withoutSchema()->type(Type::string());

        $result = $parser('field', $schema, [new ValidationRule('between', ['3', '10'])], new NestedRuleset());

        $compiled = $result->schema()->compile();

        expect($compiled['minLength'])->toBe(3)
            ->and($compiled['maxLength'])->toBe(10);
    });

    it('sets minimum and maximum for between rule on number type', function (): void {
        $parser = new ComparisonConstraintParser();
        $schema = LooseFluentDescriptor::withoutSchema()->type(Type::number());

        $result = $parser('field', $schema, [new ValidationRule('between', ['3', '10'])], new NestedRuleset());

        $compiled = $result->schema()->compile();

        expect($compiled['minimum'])->toBe(3)
            ->and($compiled['maximum'])->toBe(10);
    });

    it('sets minimum and maximum for between rule on integer type', function (): void {
        $parser = new ComparisonConstraintParser();
        $schema = LooseFluentDescriptor::withoutSchema()->type(Type::integer());

        $result = $parser('field', $schema, [new ValidationRule('between', ['1', '100'])], new NestedRuleset());

        $compiled = $result->schema()->compile();

        expect($compiled['minimum'])->toBe(1)
            ->and($compiled['maximum'])->toBe(100);
    });

    it('sets minItems and maxItems for between rule on array type', function (): void {
        $parser = new ComparisonConstraintParser();
        $schema = LooseFluentDescriptor::withoutSchema()->type(Type::array());

        $result = $parser('field', $schema, [new ValidationRule('between', ['2', '5'])], new NestedRuleset());

        $compiled = $result->schema()->compile();

        expect($compiled['minItems'])->toBe(2)
            ->and($compiled['maxItems'])->toBe(5);
    });

    it('sets minLength and maxLength for size rule on string type', function (): void {
        $parser = new ComparisonConstraintParser();
        $schema = LooseFluentDescriptor::withoutSchema()->type(Type::string());

        $result = $parser('field', $schema, [new ValidationRule('size', ['5'])], new NestedRuleset());

        $compiled = $result->schema()->compile();

        expect($compiled['minLength'])->toBe(5)
            ->and($compiled['maxLength'])->toBe(5);
    });

    it('sets minimum and maximum for size rule on number type', function (): void {
        $parser = new ComparisonConstraintParser();
        $schema = LooseFluentDescriptor::withoutSchema()->type(Type::number());

        $result = $parser('field', $schema, [new ValidationRule('size', ['42'])], new NestedRuleset());

        $compiled = $result->schema()->compile();

        expect($compiled['minimum'])->toBe(42)
            ->and($compiled['maximum'])->toBe(42);
    });

    it('sets minItems and maxItems for size rule on array type', function (): void {
        $parser = new ComparisonConstraintParser();
        $schema = LooseFluentDescriptor::withoutSchema()->type(Type::array());

        $result = $parser('field', $schema, [new ValidationRule('size', ['3'])], new NestedRuleset());

        $compiled = $result->schema()->compile();

        expect($compiled['minItems'])->toBe(3)
            ->and($compiled['maxItems'])->toBe(3);
    });

    it('falls back to string constraints when type is not set', function (): void {
        $parser = new ComparisonConstraintParser();
        $schema = LooseFluentDescriptor::withoutSchema();

        $result = $parser('field', $schema, [new ValidationRule('between', ['3', '10'])], new NestedRuleset());

        $compiled = $result->schema()->compile();

        expect($compiled['minLength'])->toBe(3)
            ->and($compiled['maxLength'])->toBe(10);
    });

    it('does not modify schema for non-comparison rules', function (): void {
        $parser = new ComparisonConstraintParser();
        $schema = LooseFluentDescriptor::withoutSchema();

        $result = $parser('field', $schema, [new ValidationRule('required'), new ValidationRule('string')], new NestedRuleset());

        $compiled = $result->schema()->compile();

        expect($compiled)->not->toHaveKey('minLength')
            ->and($compiled)->not->toHaveKey('maxLength')
            ->and($compiled)->not->toHaveKey('minimum')
            ->and($compiled)->not->toHaveKey('maximum');
    });
})->covers(ComparisonConstraintParser::class);
