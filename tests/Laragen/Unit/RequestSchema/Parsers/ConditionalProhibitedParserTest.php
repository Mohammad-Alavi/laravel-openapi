<?php

declare(strict_types=1);

use FluentJsonSchema\FluentSchema;
use MohammadAlavi\Laragen\RequestSchema\Parsers\ConditionalProhibitedParser;

describe(class_basename(ConditionalProhibitedParser::class), function (): void {
    it('generates if/then for prohibited_if rule', function (): void {
        $parser = new ConditionalProhibitedParser();
        $baseSchema = FluentSchema::make();
        $allRules = [
            'coupon' => ['##_VALIDATION_RULES_##' => [['prohibited_if', ['type', 'free']]]],
        ];

        $contextual = $parser->withContext($baseSchema, $allRules, null);
        $schema = FluentSchema::make();

        $result = $contextual('coupon', $schema, [['prohibited_if', ['type', 'free']]], []);

        $compiled = $result->compile();

        expect($compiled)->toHaveKey('if')
            ->and($compiled['if']['properties']['type'])->toBe(['const' => 'free'])
            ->and($compiled['then'])->toHaveKey('not');
    });

    it('generates if/then for prohibited_unless rule', function (): void {
        $parser = new ConditionalProhibitedParser();
        $baseSchema = FluentSchema::make();
        $allRules = [
            'coupon' => ['##_VALIDATION_RULES_##' => [['prohibited_unless', ['type', 'premium']]]],
        ];

        $contextual = $parser->withContext($baseSchema, $allRules, null);
        $schema = FluentSchema::make();

        $result = $contextual('coupon', $schema, [['prohibited_unless', ['type', 'premium']]], []);

        $compiled = $result->compile();

        expect($compiled)->toHaveKey('if')
            ->and($compiled['if']['properties']['type'])->toBe(['const' => 'premium'])
            ->and($compiled['else'])->toHaveKey('not');
    });

    it('generates allOf with not for prohibits rule', function (): void {
        $parser = new ConditionalProhibitedParser();
        $baseSchema = FluentSchema::make();
        $allRules = [
            'email' => ['##_VALIDATION_RULES_##' => [['prohibits', ['phone', 'fax']]]],
        ];

        $contextual = $parser->withContext($baseSchema, $allRules, null);
        $schema = FluentSchema::make();

        $result = $contextual('email', $schema, [['prohibits', ['phone', 'fax']]], []);

        $compiled = $result->compile();

        expect($compiled)->toHaveKey('if')
            ->and($compiled['if'])->toBe(['required' => ['email']])
            ->and($compiled['then'])->toHaveKey('not');
    });

    it('returns schema unchanged without context', function (): void {
        $parser = new ConditionalProhibitedParser();
        $schema = FluentSchema::make();

        $result = $parser('field', $schema, [['prohibited_if', ['type', 'free']]], []);

        $compiled = $result->compile();

        expect($compiled)->not->toHaveKey('if');
    });

    it('does not modify schema for non-prohibited rules', function (): void {
        $parser = new ConditionalProhibitedParser();
        $baseSchema = FluentSchema::make();
        $allRules = [
            'name' => ['##_VALIDATION_RULES_##' => [['required', []]]],
        ];

        $contextual = $parser->withContext($baseSchema, $allRules, null);
        $schema = FluentSchema::make();

        $result = $contextual('name', $schema, [['required', []]], []);

        $compiled = $result->compile();

        expect($compiled)->not->toHaveKey('if');
    });
})->covers(ConditionalProhibitedParser::class);
