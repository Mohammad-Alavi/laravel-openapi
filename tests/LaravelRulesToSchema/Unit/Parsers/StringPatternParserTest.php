<?php

declare(strict_types=1);

use MohammadAlavi\LaravelRulesToSchema\NestedRuleset;
use MohammadAlavi\LaravelRulesToSchema\Parsers\StringPatternParser;
use MohammadAlavi\LaravelRulesToSchema\ValidationRule;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\LooseFluentDescriptor;

describe(class_basename(StringPatternParser::class), function (): void {
    it('sets pattern for starts_with rule', function (): void {
        $parser = new StringPatternParser();
        $schema = LooseFluentDescriptor::withoutSchema();

        $result = $parser('field', $schema, [new ValidationRule('starts_with', ['foo', 'bar'])], new NestedRuleset());

        $compiled = $result->schema()->compile();

        expect($compiled['pattern'])->toBe('^(foo|bar)');
    });

    it('sets pattern for ends_with rule', function (): void {
        $parser = new StringPatternParser();
        $schema = LooseFluentDescriptor::withoutSchema();

        $result = $parser('field', $schema, [new ValidationRule('ends_with', ['foo', 'bar'])], new NestedRuleset());

        $compiled = $result->schema()->compile();

        expect($compiled['pattern'])->toBe('(foo|bar)$');
    });

    it('sets pattern for doesnt_start_with rule', function (): void {
        $parser = new StringPatternParser();
        $schema = LooseFluentDescriptor::withoutSchema();

        $result = $parser('field', $schema, [new ValidationRule('doesnt_start_with', ['foo', 'bar'])], new NestedRuleset());

        $compiled = $result->schema()->compile();

        expect($compiled['pattern'])->toBe('^(?!(foo|bar))');
    });

    it('sets pattern for doesnt_end_with rule', function (): void {
        $parser = new StringPatternParser();
        $schema = LooseFluentDescriptor::withoutSchema();

        $result = $parser('field', $schema, [new ValidationRule('doesnt_end_with', ['foo', 'bar'])], new NestedRuleset());

        $compiled = $result->schema()->compile();

        expect($compiled['pattern'])->toBe('(?!.*(foo|bar)$)');
    });

    it('sets pattern for lowercase rule', function (): void {
        $parser = new StringPatternParser();
        $schema = LooseFluentDescriptor::withoutSchema();

        $result = $parser('field', $schema, [new ValidationRule('lowercase')], new NestedRuleset());

        $compiled = $result->schema()->compile();

        expect($compiled['pattern'])->toBe('^[^A-Z]*$');
    });

    it('sets pattern for uppercase rule', function (): void {
        $parser = new StringPatternParser();
        $schema = LooseFluentDescriptor::withoutSchema();

        $result = $parser('field', $schema, [new ValidationRule('uppercase')], new NestedRuleset());

        $compiled = $result->schema()->compile();

        expect($compiled['pattern'])->toBe('^[^a-z]*$');
    });

    it('sets pattern for ascii rule', function (): void {
        $parser = new StringPatternParser();
        $schema = LooseFluentDescriptor::withoutSchema();

        $result = $parser('field', $schema, [new ValidationRule('ascii')], new NestedRuleset());

        $compiled = $result->schema()->compile();

        expect($compiled['pattern'])->toBe('^[\x20-\x7E]*$');
    });

    it('sets pattern for hex_color rule', function (): void {
        $parser = new StringPatternParser();
        $schema = LooseFluentDescriptor::withoutSchema();

        $result = $parser('field', $schema, [new ValidationRule('hex_color')], new NestedRuleset());

        $compiled = $result->schema()->compile();

        expect($compiled['pattern'])->toBe('^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$');
    });

    it('escapes regex special characters in starts_with values', function (): void {
        $parser = new StringPatternParser();
        $schema = LooseFluentDescriptor::withoutSchema();

        $result = $parser('field', $schema, [new ValidationRule('starts_with', ['foo.bar', 'baz+qux'])], new NestedRuleset());

        $compiled = $result->schema()->compile();

        expect($compiled['pattern'])->toBe('^(foo\.bar|baz\+qux)');
    });

    it('does not modify schema for non-pattern rules', function (): void {
        $parser = new StringPatternParser();
        $schema = LooseFluentDescriptor::withoutSchema();

        $result = $parser('field', $schema, [new ValidationRule('required'), new ValidationRule('string')], new NestedRuleset());

        $compiled = $result->schema()->compile();

        expect($compiled)->not->toHaveKey('pattern');
    });

    it('sets string type for pattern rules', function (): void {
        $parser = new StringPatternParser();
        $schema = LooseFluentDescriptor::withoutSchema();

        $result = $parser('field', $schema, [new ValidationRule('lowercase')], new NestedRuleset());

        $compiled = $result->schema()->compile();

        expect($compiled['type'])->toBe('string');
    });

    it('handles single value for starts_with', function (): void {
        $parser = new StringPatternParser();
        $schema = LooseFluentDescriptor::withoutSchema();

        $result = $parser('field', $schema, [new ValidationRule('starts_with', ['prefix'])], new NestedRuleset());

        $compiled = $result->schema()->compile();

        expect($compiled['pattern'])->toBe('^(prefix)');
    });
})->covers(StringPatternParser::class);
