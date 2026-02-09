<?php

declare(strict_types=1);

use FluentJsonSchema\FluentSchema;
use MohammadAlavi\Laragen\RequestSchema\Parsers\ConditionalAcceptedParser;

describe(class_basename(ConditionalAcceptedParser::class), function (): void {
    it('generates if/then for accepted_if rule', function (): void {
        $parser = new ConditionalAcceptedParser();
        $baseSchema = FluentSchema::make();
        $allRules = [
            'terms' => ['##_VALIDATION_RULES_##' => [['accepted_if', ['role', 'admin']]]],
        ];

        $contextual = $parser->withContext($baseSchema, $allRules, null);
        $schema = FluentSchema::make();

        $result = $contextual('terms', $schema, [['accepted_if', ['role', 'admin']]], []);

        $compiled = $result->compile();

        expect($compiled)->toHaveKey('if')
            ->and($compiled['if']['properties']['role'])->toBe(['const' => 'admin'])
            ->and($compiled['then']['type'])->toBe('boolean')
            ->and($compiled['then']['const'])->toBeTrue();
    });

    it('generates if/then for declined_if rule', function (): void {
        $parser = new ConditionalAcceptedParser();
        $baseSchema = FluentSchema::make();
        $allRules = [
            'marketing' => ['##_VALIDATION_RULES_##' => [['declined_if', ['privacy', 'strict']]]],
        ];

        $contextual = $parser->withContext($baseSchema, $allRules, null);
        $schema = FluentSchema::make();

        $result = $contextual('marketing', $schema, [['declined_if', ['privacy', 'strict']]], []);

        $compiled = $result->compile();

        expect($compiled)->toHaveKey('if')
            ->and($compiled['if']['properties']['privacy'])->toBe(['const' => 'strict'])
            ->and($compiled['then']['type'])->toBe('boolean')
            ->and($compiled['then']['const'])->toBeFalse();
    });

    it('returns schema unchanged without context', function (): void {
        $parser = new ConditionalAcceptedParser();
        $schema = FluentSchema::make();

        $result = $parser('field', $schema, [['accepted_if', ['role', 'admin']]], []);

        $compiled = $result->compile();

        expect($compiled)->not->toHaveKey('if');
    });

    it('does not modify schema for non-conditional-accepted rules', function (): void {
        $parser = new ConditionalAcceptedParser();
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
})->covers(ConditionalAcceptedParser::class);
