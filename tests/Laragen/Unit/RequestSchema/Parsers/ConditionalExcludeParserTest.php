<?php

declare(strict_types=1);

use FluentJsonSchema\FluentSchema;
use MohammadAlavi\Laragen\RequestSchema\Parsers\ConditionalExcludeParser;

describe(class_basename(ConditionalExcludeParser::class), function (): void {
    it('generates if/then for exclude_if rule', function (): void {
        $parser = new ConditionalExcludeParser();
        $baseSchema = FluentSchema::make();
        $allRules = [
            'reason' => ['##_VALIDATION_RULES_##' => [['exclude_if', ['type', 'free']]]],
        ];

        $contextual = $parser->withContext($baseSchema, $allRules, null);
        $schema = FluentSchema::make();

        $result = $contextual('reason', $schema, [['exclude_if', ['type', 'free']]], []);

        $compiled = $result->compile();

        expect($compiled)->toHaveKey('if')
            ->and($compiled['if']['properties']['type'])->toBe(['const' => 'free'])
            ->and($compiled['then'])->toHaveKey('not');
    });

    it('generates if/then for exclude_unless rule', function (): void {
        $parser = new ConditionalExcludeParser();
        $baseSchema = FluentSchema::make();
        $allRules = [
            'reason' => ['##_VALIDATION_RULES_##' => [['exclude_unless', ['type', 'premium']]]],
        ];

        $contextual = $parser->withContext($baseSchema, $allRules, null);
        $schema = FluentSchema::make();

        $result = $contextual('reason', $schema, [['exclude_unless', ['type', 'premium']]], []);

        $compiled = $result->compile();

        expect($compiled)->toHaveKey('if')
            ->and($compiled['if']['properties']['type'])->toBe(['const' => 'premium'])
            ->and($compiled['else'])->toHaveKey('not');
    });

    it('generates if/then for exclude_with rule', function (): void {
        $parser = new ConditionalExcludeParser();
        $baseSchema = FluentSchema::make();
        $allRules = [
            'nickname' => ['##_VALIDATION_RULES_##' => [['exclude_with', ['username']]]],
        ];

        $contextual = $parser->withContext($baseSchema, $allRules, null);
        $schema = FluentSchema::make();

        $result = $contextual('nickname', $schema, [['exclude_with', ['username']]], []);

        $compiled = $result->compile();

        expect($compiled)->toHaveKey('if')
            ->and($compiled['if'])->toBe(['required' => ['username']])
            ->and($compiled['then'])->toHaveKey('not');
    });

    it('generates if/then for exclude_without rule', function (): void {
        $parser = new ConditionalExcludeParser();
        $baseSchema = FluentSchema::make();
        $allRules = [
            'nickname' => ['##_VALIDATION_RULES_##' => [['exclude_without', ['username']]]],
        ];

        $contextual = $parser->withContext($baseSchema, $allRules, null);
        $schema = FluentSchema::make();

        $result = $contextual('nickname', $schema, [['exclude_without', ['username']]], []);

        $compiled = $result->compile();

        expect($compiled)->toHaveKey('if')
            ->and($compiled['then'])->toHaveKey('not');
    });

    it('generates if/then for missing_if rule', function (): void {
        $parser = new ConditionalExcludeParser();
        $baseSchema = FluentSchema::make();
        $allRules = [
            'field' => ['##_VALIDATION_RULES_##' => [['missing_if', ['status', 'inactive']]]],
        ];

        $contextual = $parser->withContext($baseSchema, $allRules, null);
        $schema = FluentSchema::make();

        $result = $contextual('field', $schema, [['missing_if', ['status', 'inactive']]], []);

        $compiled = $result->compile();

        expect($compiled)->toHaveKey('if')
            ->and($compiled['if']['properties']['status'])->toBe(['const' => 'inactive']);
    });

    it('generates if/then for missing_unless rule', function (): void {
        $parser = new ConditionalExcludeParser();
        $baseSchema = FluentSchema::make();
        $allRules = [
            'field' => ['##_VALIDATION_RULES_##' => [['missing_unless', ['status', 'active']]]],
        ];

        $contextual = $parser->withContext($baseSchema, $allRules, null);
        $schema = FluentSchema::make();

        $result = $contextual('field', $schema, [['missing_unless', ['status', 'active']]], []);

        $compiled = $result->compile();

        expect($compiled)->toHaveKey('if')
            ->and($compiled['else'])->toHaveKey('not');
    });

    it('generates if/then for missing_with rule', function (): void {
        $parser = new ConditionalExcludeParser();
        $baseSchema = FluentSchema::make();
        $allRules = [
            'field' => ['##_VALIDATION_RULES_##' => [['missing_with', ['other']]]],
        ];

        $contextual = $parser->withContext($baseSchema, $allRules, null);
        $schema = FluentSchema::make();

        $result = $contextual('field', $schema, [['missing_with', ['other']]], []);

        $compiled = $result->compile();

        expect($compiled)->toHaveKey('if')
            ->and($compiled['if'])->toBe(['required' => ['other']]);
    });

    it('generates if/then for missing_with_all rule', function (): void {
        $parser = new ConditionalExcludeParser();
        $baseSchema = FluentSchema::make();
        $allRules = [
            'field' => ['##_VALIDATION_RULES_##' => [['missing_with_all', ['a', 'b']]]],
        ];

        $contextual = $parser->withContext($baseSchema, $allRules, null);
        $schema = FluentSchema::make();

        $result = $contextual('field', $schema, [['missing_with_all', ['a', 'b']]], []);

        $compiled = $result->compile();

        expect($compiled)->toHaveKey('if')
            ->and($compiled['if'])->toBe(['required' => ['a', 'b']]);
    });

    it('returns schema unchanged without context', function (): void {
        $parser = new ConditionalExcludeParser();
        $schema = FluentSchema::make();

        $result = $parser('field', $schema, [['exclude_if', ['type', 'free']]], []);

        $compiled = $result->compile();

        expect($compiled)->not->toHaveKey('if');
    });

    it('does not modify schema for non-exclude rules', function (): void {
        $parser = new ConditionalExcludeParser();
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
})->covers(ConditionalExcludeParser::class);
