<?php

declare(strict_types=1);

use MohammadAlavi\LaravelRulesToSchema\NestedRuleset;
use MohammadAlavi\LaravelRulesToSchema\Parsers\ConditionalExcludeParser;
use MohammadAlavi\LaravelRulesToSchema\ValidationRule;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\LooseFluentDescriptor;

describe(class_basename(ConditionalExcludeParser::class), function (): void {
    it('generates if/then for exclude_if rule', function (): void {
        $parser = new ConditionalExcludeParser();
        $baseSchema = LooseFluentDescriptor::withoutSchema();
        $allRules = [
            'reason' => new NestedRuleset([new ValidationRule('exclude_if', ['type', 'free'])]),
        ];

        $contextual = $parser->withContext($baseSchema, $allRules, null);
        $schema = LooseFluentDescriptor::withoutSchema();

        $result = $contextual('reason', $schema, [new ValidationRule('exclude_if', ['type', 'free'])], new NestedRuleset());

        $compiled = $result->compile();

        expect($compiled)->toHaveKey('if')
            ->and($compiled['if']['properties']['type'])->toBe(['const' => 'free'])
            ->and($compiled['then'])->toHaveKey('not');
    });

    it('generates if/then for exclude_unless rule', function (): void {
        $parser = new ConditionalExcludeParser();
        $baseSchema = LooseFluentDescriptor::withoutSchema();
        $allRules = [
            'reason' => new NestedRuleset([new ValidationRule('exclude_unless', ['type', 'premium'])]),
        ];

        $contextual = $parser->withContext($baseSchema, $allRules, null);
        $schema = LooseFluentDescriptor::withoutSchema();

        $result = $contextual('reason', $schema, [new ValidationRule('exclude_unless', ['type', 'premium'])], new NestedRuleset());

        $compiled = $result->compile();

        expect($compiled)->toHaveKey('if')
            ->and($compiled['if']['properties']['type'])->toBe(['const' => 'premium'])
            ->and($compiled['else'])->toHaveKey('not');
    });

    it('generates if/then for exclude_with rule', function (): void {
        $parser = new ConditionalExcludeParser();
        $baseSchema = LooseFluentDescriptor::withoutSchema();
        $allRules = [
            'nickname' => new NestedRuleset([new ValidationRule('exclude_with', ['username'])]),
        ];

        $contextual = $parser->withContext($baseSchema, $allRules, null);
        $schema = LooseFluentDescriptor::withoutSchema();

        $result = $contextual('nickname', $schema, [new ValidationRule('exclude_with', ['username'])], new NestedRuleset());

        $compiled = $result->compile();

        expect($compiled)->toHaveKey('if')
            ->and($compiled['if'])->toBe(['required' => ['username']])
            ->and($compiled['then'])->toHaveKey('not');
    });

    it('generates if/then for exclude_without rule', function (): void {
        $parser = new ConditionalExcludeParser();
        $baseSchema = LooseFluentDescriptor::withoutSchema();
        $allRules = [
            'nickname' => new NestedRuleset([new ValidationRule('exclude_without', ['username'])]),
        ];

        $contextual = $parser->withContext($baseSchema, $allRules, null);
        $schema = LooseFluentDescriptor::withoutSchema();

        $result = $contextual('nickname', $schema, [new ValidationRule('exclude_without', ['username'])], new NestedRuleset());

        $compiled = $result->compile();

        expect($compiled)->toHaveKey('if')
            ->and($compiled['then'])->toHaveKey('not');
    });

    it('generates if/then for missing_if rule', function (): void {
        $parser = new ConditionalExcludeParser();
        $baseSchema = LooseFluentDescriptor::withoutSchema();
        $allRules = [
            'field' => new NestedRuleset([new ValidationRule('missing_if', ['status', 'inactive'])]),
        ];

        $contextual = $parser->withContext($baseSchema, $allRules, null);
        $schema = LooseFluentDescriptor::withoutSchema();

        $result = $contextual('field', $schema, [new ValidationRule('missing_if', ['status', 'inactive'])], new NestedRuleset());

        $compiled = $result->compile();

        expect($compiled)->toHaveKey('if')
            ->and($compiled['if']['properties']['status'])->toBe(['const' => 'inactive']);
    });

    it('generates if/then for missing_unless rule', function (): void {
        $parser = new ConditionalExcludeParser();
        $baseSchema = LooseFluentDescriptor::withoutSchema();
        $allRules = [
            'field' => new NestedRuleset([new ValidationRule('missing_unless', ['status', 'active'])]),
        ];

        $contextual = $parser->withContext($baseSchema, $allRules, null);
        $schema = LooseFluentDescriptor::withoutSchema();

        $result = $contextual('field', $schema, [new ValidationRule('missing_unless', ['status', 'active'])], new NestedRuleset());

        $compiled = $result->compile();

        expect($compiled)->toHaveKey('if')
            ->and($compiled['else'])->toHaveKey('not');
    });

    it('generates if/then for missing_with rule', function (): void {
        $parser = new ConditionalExcludeParser();
        $baseSchema = LooseFluentDescriptor::withoutSchema();
        $allRules = [
            'field' => new NestedRuleset([new ValidationRule('missing_with', ['other'])]),
        ];

        $contextual = $parser->withContext($baseSchema, $allRules, null);
        $schema = LooseFluentDescriptor::withoutSchema();

        $result = $contextual('field', $schema, [new ValidationRule('missing_with', ['other'])], new NestedRuleset());

        $compiled = $result->compile();

        expect($compiled)->toHaveKey('if')
            ->and($compiled['if'])->toBe(['required' => ['other']]);
    });

    it('generates if/then for missing_with_all rule', function (): void {
        $parser = new ConditionalExcludeParser();
        $baseSchema = LooseFluentDescriptor::withoutSchema();
        $allRules = [
            'field' => new NestedRuleset([new ValidationRule('missing_with_all', ['a', 'b'])]),
        ];

        $contextual = $parser->withContext($baseSchema, $allRules, null);
        $schema = LooseFluentDescriptor::withoutSchema();

        $result = $contextual('field', $schema, [new ValidationRule('missing_with_all', ['a', 'b'])], new NestedRuleset());

        $compiled = $result->compile();

        expect($compiled)->toHaveKey('if')
            ->and($compiled['if'])->toBe(['required' => ['a', 'b']]);
    });

    it('returns schema unchanged without context', function (): void {
        $parser = new ConditionalExcludeParser();
        $schema = LooseFluentDescriptor::withoutSchema();

        $result = $parser('field', $schema, [new ValidationRule('exclude_if', ['type', 'free'])], new NestedRuleset());

        $compiled = $result->compile();

        expect($compiled)->not->toHaveKey('if');
    });

    it('does not modify schema for non-exclude rules', function (): void {
        $parser = new ConditionalExcludeParser();
        $baseSchema = LooseFluentDescriptor::withoutSchema();
        $allRules = [
            'name' => new NestedRuleset([new ValidationRule('required')]),
        ];

        $contextual = $parser->withContext($baseSchema, $allRules, null);
        $schema = LooseFluentDescriptor::withoutSchema();

        $result = $contextual('name', $schema, [new ValidationRule('required')], new NestedRuleset());

        $compiled = $result->compile();

        expect($compiled)->not->toHaveKey('if');
    });
})->covers(ConditionalExcludeParser::class);
