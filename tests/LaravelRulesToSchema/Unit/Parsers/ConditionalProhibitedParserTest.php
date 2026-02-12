<?php

use MohammadAlavi\LaravelRulesToSchema\NestedRuleset;
use MohammadAlavi\LaravelRulesToSchema\Parsers\ConditionalProhibitedParser;
use MohammadAlavi\LaravelRulesToSchema\ValidationRule;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\LooseFluentDescriptor;

describe(class_basename(ConditionalProhibitedParser::class), function (): void {
    it('generates if/then for prohibited_if rule', function (): void {
        $parser = new ConditionalProhibitedParser();
        $baseSchema = LooseFluentDescriptor::withoutSchema();
        $allRules = [
            'coupon' => new NestedRuleset([new ValidationRule('prohibited_if', ['type', 'free'])]),
        ];

        $contextual = $parser->withContext($baseSchema, $allRules, null);
        $schema = LooseFluentDescriptor::withoutSchema();

        $result = $contextual('coupon', $schema, [new ValidationRule('prohibited_if', ['type', 'free'])], new NestedRuleset());

        $compiled = $result->schema()->compile();

        expect($compiled)->toHaveKey('if')
            ->and($compiled['if']['properties']['type'])->toBe(['const' => 'free'])
            ->and($compiled['then'])->toHaveKey('not');
    });

    it('generates if/then for prohibited_unless rule', function (): void {
        $parser = new ConditionalProhibitedParser();
        $baseSchema = LooseFluentDescriptor::withoutSchema();
        $allRules = [
            'coupon' => new NestedRuleset([new ValidationRule('prohibited_unless', ['type', 'premium'])]),
        ];

        $contextual = $parser->withContext($baseSchema, $allRules, null);
        $schema = LooseFluentDescriptor::withoutSchema();

        $result = $contextual('coupon', $schema, [new ValidationRule('prohibited_unless', ['type', 'premium'])], new NestedRuleset());

        $compiled = $result->schema()->compile();

        expect($compiled)->toHaveKey('if')
            ->and($compiled['if']['properties']['type'])->toBe(['const' => 'premium'])
            ->and($compiled['else'])->toHaveKey('not');
    });

    it('generates allOf with not for prohibits rule', function (): void {
        $parser = new ConditionalProhibitedParser();
        $baseSchema = LooseFluentDescriptor::withoutSchema();
        $allRules = [
            'email' => new NestedRuleset([new ValidationRule('prohibits', ['phone', 'fax'])]),
        ];

        $contextual = $parser->withContext($baseSchema, $allRules, null);
        $schema = LooseFluentDescriptor::withoutSchema();

        $result = $contextual('email', $schema, [new ValidationRule('prohibits', ['phone', 'fax'])], new NestedRuleset());

        $compiled = $result->schema()->compile();

        expect($compiled)->toHaveKey('if')
            ->and($compiled['if'])->toBe(['required' => ['email']])
            ->and($compiled['then'])->toHaveKey('not');
    });

    it('returns schema unchanged without context', function (): void {
        $parser = new ConditionalProhibitedParser();
        $schema = LooseFluentDescriptor::withoutSchema();

        $result = $parser('field', $schema, [new ValidationRule('prohibited_if', ['type', 'free'])], new NestedRuleset());

        $compiled = $result->schema()->compile();

        expect($compiled)->not->toHaveKey('if');
    });

    it('does not modify schema for non-prohibited rules', function (): void {
        $parser = new ConditionalProhibitedParser();
        $baseSchema = LooseFluentDescriptor::withoutSchema();
        $allRules = [
            'name' => new NestedRuleset([new ValidationRule('required')]),
        ];

        $contextual = $parser->withContext($baseSchema, $allRules, null);
        $schema = LooseFluentDescriptor::withoutSchema();

        $result = $contextual('name', $schema, [new ValidationRule('required')], new NestedRuleset());

        $compiled = $result->schema()->compile();

        expect($compiled)->not->toHaveKey('if');
    });
})->covers(ConditionalProhibitedParser::class);
