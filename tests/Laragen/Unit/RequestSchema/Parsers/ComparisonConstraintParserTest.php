<?php

declare(strict_types=1);

use FluentJsonSchema\FluentSchema;
use MohammadAlavi\Laragen\RequestSchema\Parsers\ComparisonConstraintParser;

describe(class_basename(ComparisonConstraintParser::class), function (): void {
    it('sets minLength and maxLength for between rule on string type', function (): void {
        $parser = new ComparisonConstraintParser();
        $schema = FluentSchema::make();
        $schema->type()->string();

        $result = $parser('field', $schema, [['between', ['3', '10']]], []);

        $compiled = $result->compile();

        expect($compiled['minLength'])->toBe(3)
            ->and($compiled['maxLength'])->toBe(10);
    });

    it('sets minimum and maximum for between rule on number type', function (): void {
        $parser = new ComparisonConstraintParser();
        $schema = FluentSchema::make();
        $schema->type()->number();

        $result = $parser('field', $schema, [['between', ['3', '10']]], []);

        $compiled = $result->compile();

        expect($compiled['minimum'])->toBe(3)
            ->and($compiled['maximum'])->toBe(10);
    });

    it('sets minimum and maximum for between rule on integer type', function (): void {
        $parser = new ComparisonConstraintParser();
        $schema = FluentSchema::make();
        $schema->type()->integer();

        $result = $parser('field', $schema, [['between', ['1', '100']]], []);

        $compiled = $result->compile();

        expect($compiled['minimum'])->toBe(1)
            ->and($compiled['maximum'])->toBe(100);
    });

    it('sets minItems and maxItems for between rule on array type', function (): void {
        $parser = new ComparisonConstraintParser();
        $schema = FluentSchema::make();
        $schema->type()->array();

        $result = $parser('field', $schema, [['between', ['2', '5']]], []);

        $compiled = $result->compile();

        expect($compiled['minItems'])->toBe(2)
            ->and($compiled['maxItems'])->toBe(5);
    });

    it('sets minLength and maxLength for size rule on string type', function (): void {
        $parser = new ComparisonConstraintParser();
        $schema = FluentSchema::make();
        $schema->type()->string();

        $result = $parser('field', $schema, [['size', ['5']]], []);

        $compiled = $result->compile();

        expect($compiled['minLength'])->toBe(5)
            ->and($compiled['maxLength'])->toBe(5);
    });

    it('sets minimum and maximum for size rule on number type', function (): void {
        $parser = new ComparisonConstraintParser();
        $schema = FluentSchema::make();
        $schema->type()->number();

        $result = $parser('field', $schema, [['size', ['42']]], []);

        $compiled = $result->compile();

        expect($compiled['minimum'])->toBe(42)
            ->and($compiled['maximum'])->toBe(42);
    });

    it('sets minItems and maxItems for size rule on array type', function (): void {
        $parser = new ComparisonConstraintParser();
        $schema = FluentSchema::make();
        $schema->type()->array();

        $result = $parser('field', $schema, [['size', ['3']]], []);

        $compiled = $result->compile();

        expect($compiled['minItems'])->toBe(3)
            ->and($compiled['maxItems'])->toBe(3);
    });

    it('falls back to string constraints when type is not set', function (): void {
        $parser = new ComparisonConstraintParser();
        $schema = FluentSchema::make();

        $result = $parser('field', $schema, [['between', ['3', '10']]], []);

        $compiled = $result->compile();

        expect($compiled['minLength'])->toBe(3)
            ->and($compiled['maxLength'])->toBe(10);
    });

    it('does not modify schema for non-comparison rules', function (): void {
        $parser = new ComparisonConstraintParser();
        $schema = FluentSchema::make();

        $result = $parser('field', $schema, [['required', []], ['string', []]], []);

        $compiled = $result->compile();

        expect($compiled)->not->toHaveKey('minLength')
            ->and($compiled)->not->toHaveKey('maxLength')
            ->and($compiled)->not->toHaveKey('minimum')
            ->and($compiled)->not->toHaveKey('maximum');
    });
})->covers(ComparisonConstraintParser::class);
