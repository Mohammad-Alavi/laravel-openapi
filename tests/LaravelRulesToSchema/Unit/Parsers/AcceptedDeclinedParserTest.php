<?php

declare(strict_types=1);

use MohammadAlavi\LaravelRulesToSchema\Parsers\AcceptedDeclinedParser;
use MohammadAlavi\LaravelRulesToSchema\ValidationRule;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\LooseFluentDescriptor;

describe(class_basename(AcceptedDeclinedParser::class), function (): void {
    it('sets boolean type for accepted rule', function (): void {
        $parser = new AcceptedDeclinedParser();
        $schema = LooseFluentDescriptor::withoutSchema();

        $result = $parser('terms', $schema, [new ValidationRule('accepted')], []);

        $compiled = $result->compile();

        expect($compiled['type'])->toBe('boolean');
    });

    it('sets boolean type for declined rule', function (): void {
        $parser = new AcceptedDeclinedParser();
        $schema = LooseFluentDescriptor::withoutSchema();

        $result = $parser('opt_out', $schema, [new ValidationRule('declined')], []);

        $compiled = $result->compile();

        expect($compiled['type'])->toBe('boolean');
    });

    it('does not modify schema for non-accepted/declined rules', function (): void {
        $parser = new AcceptedDeclinedParser();
        $schema = LooseFluentDescriptor::withoutSchema();

        $result = $parser('field', $schema, [new ValidationRule('required'), new ValidationRule('string')], []);

        $compiled = $result->compile();

        expect($compiled)->not->toHaveKey('type');
    });
})->covers(AcceptedDeclinedParser::class);
