<?php

declare(strict_types=1);

use FluentJsonSchema\FluentSchema;
use MohammadAlavi\Laragen\RequestSchema\Parsers\NumericConstraintParser;

describe(class_basename(NumericConstraintParser::class), function (): void {
    it('sets multipleOf for multiple_of rule', function (): void {
        $parser = new NumericConstraintParser();
        $schema = FluentSchema::make();

        $result = $parser('field', $schema, [['multiple_of', ['3']]], []);

        $compiled = $result->compile();

        expect($compiled['multipleOf'])->toBe(3);
    });

    it('sets maximum for max_digits rule', function (): void {
        $parser = new NumericConstraintParser();
        $schema = FluentSchema::make();

        $result = $parser('field', $schema, [['max_digits', ['5']]], []);

        $compiled = $result->compile();

        expect($compiled['maximum'])->toBe(99999);
    });

    it('sets minimum for min_digits rule', function (): void {
        $parser = new NumericConstraintParser();
        $schema = FluentSchema::make();

        $result = $parser('field', $schema, [['min_digits', ['3']]], []);

        $compiled = $result->compile();

        expect($compiled['minimum'])->toBe(100);
    });

    it('sets minimum to 0 for min_digits with 1 digit', function (): void {
        $parser = new NumericConstraintParser();
        $schema = FluentSchema::make();

        $result = $parser('field', $schema, [['min_digits', ['1']]], []);

        $compiled = $result->compile();

        expect($compiled['minimum'])->toBe(0);
    });

    it('handles max_digits with 1 digit', function (): void {
        $parser = new NumericConstraintParser();
        $schema = FluentSchema::make();

        $result = $parser('field', $schema, [['max_digits', ['1']]], []);

        $compiled = $result->compile();

        expect($compiled['maximum'])->toBe(9);
    });

    it('does not modify schema for non-numeric rules', function (): void {
        $parser = new NumericConstraintParser();
        $schema = FluentSchema::make();

        $result = $parser('field', $schema, [['required', []], ['string', []]], []);

        $compiled = $result->compile();

        expect($compiled)->not->toHaveKey('multipleOf')
            ->and($compiled)->not->toHaveKey('minimum')
            ->and($compiled)->not->toHaveKey('maximum');
    });

    it('handles multiple numeric rules together', function (): void {
        $parser = new NumericConstraintParser();
        $schema = FluentSchema::make();

        $result = $parser('field', $schema, [
            ['min_digits', ['2']],
            ['max_digits', ['4']],
            ['multiple_of', ['5']],
        ], []);

        $compiled = $result->compile();

        expect($compiled['minimum'])->toBe(10)
            ->and($compiled['maximum'])->toBe(9999)
            ->and($compiled['multipleOf'])->toBe(5);
    });
})->covers(NumericConstraintParser::class);
