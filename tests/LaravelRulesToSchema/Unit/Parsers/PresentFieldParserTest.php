<?php

use MohammadAlavi\LaravelRulesToSchema\NestedRuleset;
use MohammadAlavi\LaravelRulesToSchema\Parsers\PresentFieldParser;
use MohammadAlavi\LaravelRulesToSchema\ValidationRule;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\LooseFluentDescriptor;

describe(class_basename(PresentFieldParser::class), function (): void {
    it('adds field to required for present rule', function (): void {
        $parser = new PresentFieldParser();
        $baseSchema = LooseFluentDescriptor::withoutSchema();
        $allRules = [
            'token' => new NestedRuleset([new ValidationRule('present')]),
        ];

        $contextual = $parser->withContext($baseSchema, $allRules, null);
        $schema = LooseFluentDescriptor::withoutSchema();

        $result = $contextual('token', $schema, [new ValidationRule('present')], new NestedRuleset());

        $compiled = $result->schema()->compile();

        expect($compiled['required'])->toBe(['token']);
    });

    it('generates if/then for present_if rule', function (): void {
        $parser = new PresentFieldParser();
        $baseSchema = LooseFluentDescriptor::withoutSchema();
        $allRules = [
            'token' => new NestedRuleset([new ValidationRule('present_if', ['type', 'api'])]),
        ];

        $contextual = $parser->withContext($baseSchema, $allRules, null);
        $schema = LooseFluentDescriptor::withoutSchema();

        $result = $contextual('token', $schema, [new ValidationRule('present_if', ['type', 'api'])], new NestedRuleset());

        $compiled = $result->schema()->compile();

        expect($compiled)->toHaveKey('if')
            ->and($compiled['if']['properties']['type'])->toBe(['const' => 'api'])
            ->and($compiled['then'])->toBe(['required' => ['token']]);
    });

    it('generates if/else for present_unless rule', function (): void {
        $parser = new PresentFieldParser();
        $baseSchema = LooseFluentDescriptor::withoutSchema();
        $allRules = [
            'token' => new NestedRuleset([new ValidationRule('present_unless', ['type', 'guest'])]),
        ];

        $contextual = $parser->withContext($baseSchema, $allRules, null);
        $schema = LooseFluentDescriptor::withoutSchema();

        $result = $contextual('token', $schema, [new ValidationRule('present_unless', ['type', 'guest'])], new NestedRuleset());

        $compiled = $result->schema()->compile();

        expect($compiled)->toHaveKey('if')
            ->and($compiled['if']['properties']['type'])->toBe(['const' => 'guest'])
            ->and($compiled['else'])->toBe(['required' => ['token']]);
    });

    it('generates if/then for present_with rule', function (): void {
        $parser = new PresentFieldParser();
        $baseSchema = LooseFluentDescriptor::withoutSchema();
        $allRules = [
            'city' => new NestedRuleset([new ValidationRule('present_with', ['address'])]),
        ];

        $contextual = $parser->withContext($baseSchema, $allRules, null);
        $schema = LooseFluentDescriptor::withoutSchema();

        $result = $contextual('city', $schema, [new ValidationRule('present_with', ['address'])], new NestedRuleset());

        $compiled = $result->schema()->compile();

        expect($compiled)->toHaveKey('if')
            ->and($compiled['if'])->toBe(['required' => ['address']])
            ->and($compiled['then'])->toBe(['required' => ['city']]);
    });

    it('generates if/then for present_with_all rule', function (): void {
        $parser = new PresentFieldParser();
        $baseSchema = LooseFluentDescriptor::withoutSchema();
        $allRules = [
            'city' => new NestedRuleset([new ValidationRule('present_with_all', ['street', 'zip'])]),
        ];

        $contextual = $parser->withContext($baseSchema, $allRules, null);
        $schema = LooseFluentDescriptor::withoutSchema();

        $result = $contextual('city', $schema, [new ValidationRule('present_with_all', ['street', 'zip'])], new NestedRuleset());

        $compiled = $result->schema()->compile();

        expect($compiled)->toHaveKey('if')
            ->and($compiled['if'])->toBe(['required' => ['street', 'zip']])
            ->and($compiled['then'])->toBe(['required' => ['city']]);
    });

    it('returns schema unchanged without context', function (): void {
        $parser = new PresentFieldParser();
        $schema = LooseFluentDescriptor::withoutSchema();

        $result = $parser('field', $schema, [new ValidationRule('present')], new NestedRuleset());

        $compiled = $result->schema()->compile();

        expect($compiled)->not->toHaveKey('required');
    });

    it('does not modify schema for non-present rules', function (): void {
        $parser = new PresentFieldParser();
        $baseSchema = LooseFluentDescriptor::withoutSchema();
        $allRules = [
            'name' => new NestedRuleset([new ValidationRule('string')]),
        ];

        $contextual = $parser->withContext($baseSchema, $allRules, null);
        $schema = LooseFluentDescriptor::withoutSchema();

        $result = $contextual('name', $schema, [new ValidationRule('string')], new NestedRuleset());

        $compiled = $result->schema()->compile();

        expect($compiled)->not->toHaveKey('required')
            ->and($compiled)->not->toHaveKey('if');
    });
})->covers(PresentFieldParser::class);
