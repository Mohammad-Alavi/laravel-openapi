<?php

declare(strict_types=1);

use FluentJsonSchema\FluentSchema;
use MohammadAlavi\Laragen\RequestSchema\Parsers\PresentFieldParser;

describe(class_basename(PresentFieldParser::class), function (): void {
    it('adds field to required for present rule', function (): void {
        $parser = new PresentFieldParser();
        $baseSchema = FluentSchema::make();
        $allRules = [
            'token' => ['##_VALIDATION_RULES_##' => [['present', []]]],
        ];

        $contextual = $parser->withContext($baseSchema, $allRules, null);
        $schema = FluentSchema::make();

        $result = $contextual('token', $schema, [['present', []]], []);

        $compiled = $result->compile();

        expect($compiled['required'])->toBe(['token']);
    });

    it('generates if/then for present_if rule', function (): void {
        $parser = new PresentFieldParser();
        $baseSchema = FluentSchema::make();
        $allRules = [
            'token' => ['##_VALIDATION_RULES_##' => [['present_if', ['type', 'api']]]],
        ];

        $contextual = $parser->withContext($baseSchema, $allRules, null);
        $schema = FluentSchema::make();

        $result = $contextual('token', $schema, [['present_if', ['type', 'api']]], []);

        $compiled = $result->compile();

        expect($compiled)->toHaveKey('if')
            ->and($compiled['if']['properties']['type'])->toBe(['const' => 'api'])
            ->and($compiled['then'])->toBe(['required' => ['token']]);
    });

    it('generates if/else for present_unless rule', function (): void {
        $parser = new PresentFieldParser();
        $baseSchema = FluentSchema::make();
        $allRules = [
            'token' => ['##_VALIDATION_RULES_##' => [['present_unless', ['type', 'guest']]]],
        ];

        $contextual = $parser->withContext($baseSchema, $allRules, null);
        $schema = FluentSchema::make();

        $result = $contextual('token', $schema, [['present_unless', ['type', 'guest']]], []);

        $compiled = $result->compile();

        expect($compiled)->toHaveKey('if')
            ->and($compiled['if']['properties']['type'])->toBe(['const' => 'guest'])
            ->and($compiled['else'])->toBe(['required' => ['token']]);
    });

    it('generates if/then for present_with rule', function (): void {
        $parser = new PresentFieldParser();
        $baseSchema = FluentSchema::make();
        $allRules = [
            'city' => ['##_VALIDATION_RULES_##' => [['present_with', ['address']]]],
        ];

        $contextual = $parser->withContext($baseSchema, $allRules, null);
        $schema = FluentSchema::make();

        $result = $contextual('city', $schema, [['present_with', ['address']]], []);

        $compiled = $result->compile();

        expect($compiled)->toHaveKey('if')
            ->and($compiled['if'])->toBe(['required' => ['address']])
            ->and($compiled['then'])->toBe(['required' => ['city']]);
    });

    it('generates if/then for present_with_all rule', function (): void {
        $parser = new PresentFieldParser();
        $baseSchema = FluentSchema::make();
        $allRules = [
            'city' => ['##_VALIDATION_RULES_##' => [['present_with_all', ['street', 'zip']]]],
        ];

        $contextual = $parser->withContext($baseSchema, $allRules, null);
        $schema = FluentSchema::make();

        $result = $contextual('city', $schema, [['present_with_all', ['street', 'zip']]], []);

        $compiled = $result->compile();

        expect($compiled)->toHaveKey('if')
            ->and($compiled['if'])->toBe(['required' => ['street', 'zip']])
            ->and($compiled['then'])->toBe(['required' => ['city']]);
    });

    it('returns schema unchanged without context', function (): void {
        $parser = new PresentFieldParser();
        $schema = FluentSchema::make();

        $result = $parser('field', $schema, [['present', []]], []);

        $compiled = $result->compile();

        expect($compiled)->not->toHaveKey('required');
    });

    it('does not modify schema for non-present rules', function (): void {
        $parser = new PresentFieldParser();
        $baseSchema = FluentSchema::make();
        $allRules = [
            'name' => ['##_VALIDATION_RULES_##' => [['string', []]]],
        ];

        $contextual = $parser->withContext($baseSchema, $allRules, null);
        $schema = FluentSchema::make();

        $result = $contextual('name', $schema, [['string', []]], []);

        $compiled = $result->compile();

        expect($compiled)->not->toHaveKey('required')
            ->and($compiled)->not->toHaveKey('if');
    });
})->covers(PresentFieldParser::class);
