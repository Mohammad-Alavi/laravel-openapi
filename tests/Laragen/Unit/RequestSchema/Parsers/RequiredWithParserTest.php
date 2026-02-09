<?php

declare(strict_types=1);

use FluentJsonSchema\FluentSchema;
use MohammadAlavi\Laragen\RequestSchema\Parsers\RequiredWithParser;

describe(class_basename(RequiredWithParser::class), function (): void {
    it('adds if/then conditions for mutual required_with', function (): void {
        $parser = new RequiredWithParser();
        $baseSchema = FluentSchema::make()
            ->type()->object()->return();
        $nameSchema = FluentSchema::make()->type()->string()->return();
        $emailSchema = FluentSchema::make()->type()->string()->return();
        $baseSchema->object()->property('name', $nameSchema);
        $baseSchema->object()->property('email', $emailSchema);

        $allRules = [
            'name' => ['##_VALIDATION_RULES_##' => [['string', []], ['required_with', ['email']]]],
            'email' => ['##_VALIDATION_RULES_##' => [['string', []], ['required_with', ['name']]]],
        ];

        $contextual = $parser->withContext($baseSchema, $allRules, null);

        $contextual('name', $nameSchema, [['string', []], ['required_with', ['email']]], []);
        $contextual('email', $emailSchema, [['string', []], ['required_with', ['name']]], []);

        $compiled = $baseSchema->compile();

        expect($compiled)->toHaveKey('properties')
            ->and($compiled['properties'])->toHaveKey('name')
            ->and($compiled['properties'])->toHaveKey('email')
            ->and($compiled)->toHaveKey('allOf')
            ->and($compiled['allOf'])->toHaveCount(2);

        // First condition: if email is present, name is required
        $firstCondition = $compiled['allOf'][0];
        expect($firstCondition)->toHaveKey('if')
            ->and($firstCondition['if'])->toBe(['required' => ['email']])
            ->and($firstCondition['then'])->toBe(['required' => ['name']]);

        // Second condition: if name is present, email is required
        $secondCondition = $compiled['allOf'][1];
        expect($secondCondition)->toHaveKey('if')
            ->and($secondCondition['if'])->toBe(['required' => ['name']])
            ->and($secondCondition['then'])->toBe(['required' => ['email']]);
    });

    it('handles required_with with multiple arguments using anyOf in if', function (): void {
        $parser = new RequiredWithParser();
        $baseSchema = FluentSchema::make()
            ->type()->object()->return();
        $nameSchema = FluentSchema::make()->type()->string()->return();
        $emailSchema = FluentSchema::make()->type()->string()->return();
        $ageSchema = FluentSchema::make()->type()->integer()->return();
        $baseSchema->object()->property('name', $nameSchema);
        $baseSchema->object()->property('email', $emailSchema);
        $baseSchema->object()->property('age', $ageSchema);

        $allRules = [
            'name' => ['##_VALIDATION_RULES_##' => [['string', []]]],
            'email' => ['##_VALIDATION_RULES_##' => [['string', []]]],
            'age' => ['##_VALIDATION_RULES_##' => [['integer', []], ['required_with', ['name', 'email']]]],
        ];

        $contextual = $parser->withContext($baseSchema, $allRules, null);

        $contextual('name', $nameSchema, [['string', []]], []);
        $contextual('email', $emailSchema, [['string', []]], []);
        $contextual('age', $ageSchema, [['integer', []], ['required_with', ['name', 'email']]], []);

        $compiled = $baseSchema->compile();

        expect($compiled)->toHaveKey('properties')
            ->and($compiled['properties'])->toHaveKey('name')
            ->and($compiled['properties'])->toHaveKey('email')
            ->and($compiled['properties'])->toHaveKey('age')
            ->and($compiled)->toHaveKey('allOf')
            ->and($compiled['allOf'])->toHaveCount(1);

        $condition = $compiled['allOf'][0];
        expect($condition['if'])->toBe(['anyOf' => [['required' => ['name']], ['required' => ['email']]]])
            ->and($condition['then'])->toBe(['required' => ['age']]);
    });

    it('preserves properties for mixed fields with and without required_with', function (): void {
        $parser = new RequiredWithParser();
        $baseSchema = FluentSchema::make()
            ->type()->object()->return();
        $nameSchema = FluentSchema::make()->type()->string()->return();
        $emailSchema = FluentSchema::make()->type()->string()->return();
        $ageSchema = FluentSchema::make()->type()->integer()->return();
        $baseSchema->object()->property('name', $nameSchema);
        $baseSchema->object()->property('email', $emailSchema);
        $baseSchema->object()->property('age', $ageSchema);

        $allRules = [
            'name' => ['##_VALIDATION_RULES_##' => [['string', []], ['required_with', ['email']]]],
            'email' => ['##_VALIDATION_RULES_##' => [['string', []], ['required_with', ['name']]]],
            'age' => ['##_VALIDATION_RULES_##' => [['integer', []]]],
        ];

        $contextual = $parser->withContext($baseSchema, $allRules, null);

        $contextual('name', $nameSchema, [['string', []], ['required_with', ['email']]], []);
        $contextual('email', $emailSchema, [['string', []], ['required_with', ['name']]], []);
        $contextual('age', $ageSchema, [['integer', []]], []);

        $compiled = $baseSchema->compile();

        expect($compiled)->toHaveKey('properties')
            ->and($compiled['properties'])->toHaveKey('name')
            ->and($compiled['properties'])->toHaveKey('email')
            ->and($compiled['properties'])->toHaveKey('age')
            ->and($compiled)->toHaveKey('allOf')
            ->and($compiled['allOf'])->toHaveCount(2);
    });

    it('does not modify schema when no required_with rules exist', function (): void {
        $parser = new RequiredWithParser();
        $baseSchema = FluentSchema::make()
            ->type()->object()->return();
        $nameSchema = FluentSchema::make()->type()->string()->return();
        $baseSchema->object()->property('name', $nameSchema);

        $allRules = [
            'name' => ['##_VALIDATION_RULES_##' => [['string', []], ['required', []]]],
        ];

        $contextual = $parser->withContext($baseSchema, $allRules, null);

        $result = $contextual('name', $nameSchema, [['string', []], ['required', []]], []);

        $compiled = $baseSchema->compile();

        expect($compiled)->not->toHaveKey('allOf')
            ->and($compiled)->toHaveKey('properties');
    });

    it('preserves nullable type when required_with fields coexist', function (): void {
        $parser = new RequiredWithParser();
        $baseSchema = FluentSchema::make()
            ->type()->object()->return();
        $nameSchema = FluentSchema::make()->type()->string()->return();
        $emailSchema = FluentSchema::make()->type()->string()->return();
        $ageSchema = FluentSchema::make()->type()->integer()->type()->null()->return();
        $baseSchema->object()->property('name', $nameSchema);
        $baseSchema->object()->property('email', $emailSchema);
        $baseSchema->object()->property('age', $ageSchema);

        $allRules = [
            'name' => ['##_VALIDATION_RULES_##' => [['string', []], ['required_with', ['email']]]],
            'email' => ['##_VALIDATION_RULES_##' => [['string', []], ['required_with', ['name']]]],
            'age' => ['##_VALIDATION_RULES_##' => [['nullable', []], ['integer', []]]],
        ];

        $contextual = $parser->withContext($baseSchema, $allRules, null);

        $contextual('name', $nameSchema, [['string', []], ['required_with', ['email']]], []);
        $contextual('email', $emailSchema, [['string', []], ['required_with', ['name']]], []);
        $contextual('age', $ageSchema, [['nullable', []], ['integer', []]], []);

        $compiled = $baseSchema->compile();

        expect($compiled)->toHaveKey('properties')
            ->and($compiled['properties'])->toHaveKey('age')
            ->and($compiled['properties']['age'])->toHaveKey('type')
            ->and((array) $compiled['properties']['age']['type'])->toContain('null');
    });

    it('returns schema unchanged without context', function (): void {
        $parser = new RequiredWithParser();
        $schema = FluentSchema::make();

        $result = $parser('field', $schema, [['required_with', ['other']]], []);

        expect($result)->toBe($schema);
    });
})->covers(RequiredWithParser::class);
