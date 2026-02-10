<?php

declare(strict_types=1);

use MohammadAlavi\LaravelRulesToSchema\NestedRuleset;
use MohammadAlavi\LaravelRulesToSchema\Parsers\ConditionalAcceptedParser;
use MohammadAlavi\LaravelRulesToSchema\ValidationRule;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\LooseFluentDescriptor;

describe(class_basename(ConditionalAcceptedParser::class), function (): void {
    it('generates if/then for accepted_if rule', function (): void {
        $parser = new ConditionalAcceptedParser();
        $baseSchema = LooseFluentDescriptor::withoutSchema();
        $allRules = [
            'terms' => new NestedRuleset([new ValidationRule('accepted_if', ['role', 'admin'])]),
        ];

        $contextual = $parser->withContext($baseSchema, $allRules, null);
        $schema = LooseFluentDescriptor::withoutSchema();

        $result = $contextual('terms', $schema, [new ValidationRule('accepted_if', ['role', 'admin'])], new NestedRuleset());

        $compiled = $result->schema()->compile();

        expect($compiled)->toHaveKey('if')
            ->and($compiled['if']['properties']['role'])->toBe(['const' => 'admin'])
            ->and($compiled['then']['type'])->toBe('boolean')
            ->and($compiled['then']['const'])->toBeTrue();
    });

    it('generates if/then for declined_if rule', function (): void {
        $parser = new ConditionalAcceptedParser();
        $baseSchema = LooseFluentDescriptor::withoutSchema();
        $allRules = [
            'marketing' => new NestedRuleset([new ValidationRule('declined_if', ['privacy', 'strict'])]),
        ];

        $contextual = $parser->withContext($baseSchema, $allRules, null);
        $schema = LooseFluentDescriptor::withoutSchema();

        $result = $contextual('marketing', $schema, [new ValidationRule('declined_if', ['privacy', 'strict'])], new NestedRuleset());

        $compiled = $result->schema()->compile();

        expect($compiled)->toHaveKey('if')
            ->and($compiled['if']['properties']['privacy'])->toBe(['const' => 'strict'])
            ->and($compiled['then']['type'])->toBe('boolean')
            ->and($compiled['then']['const'])->toBeFalse();
    });

    it('returns schema unchanged without context', function (): void {
        $parser = new ConditionalAcceptedParser();
        $schema = LooseFluentDescriptor::withoutSchema();

        $result = $parser('field', $schema, [new ValidationRule('accepted_if', ['role', 'admin'])], new NestedRuleset());

        $compiled = $result->schema()->compile();

        expect($compiled)->not->toHaveKey('if');
    });

    it('does not modify schema for non-conditional-accepted rules', function (): void {
        $parser = new ConditionalAcceptedParser();
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
})->covers(ConditionalAcceptedParser::class);
