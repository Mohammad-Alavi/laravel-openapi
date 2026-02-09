<?php

declare(strict_types=1);

use FluentJsonSchema\FluentSchema;
use MohammadAlavi\Laragen\RequestSchema\Parsers\AdditionalConstraintParser;

describe(class_basename(AdditionalConstraintParser::class), function (): void {
    it('sets format uri for active_url rule', function (): void {
        $parser = new AdditionalConstraintParser();
        $schema = FluentSchema::make();

        $result = $parser('website', $schema, [['active_url', []]], []);

        $compiled = $result->compile();

        expect($compiled['format'])->toBe('uri');
    });

    it('sets format timezone for timezone rule', function (): void {
        $parser = new AdditionalConstraintParser();
        $schema = FluentSchema::make();

        $result = $parser('tz', $schema, [['timezone', []]], []);

        $compiled = $result->compile();

        expect($compiled['format'])->toBe('timezone');
    });

    it('sets minLength 1 for filled rule on string type', function (): void {
        $parser = new AdditionalConstraintParser();
        $schema = FluentSchema::make();
        $schema->type()->string();

        $result = $parser('name', $schema, [['filled', []]], []);

        $compiled = $result->compile();

        expect($compiled['minLength'])->toBe(1);
    });

    it('sets minItems 1 for filled rule on array type', function (): void {
        $parser = new AdditionalConstraintParser();
        $schema = FluentSchema::make();
        $schema->type()->array();

        $result = $parser('tags', $schema, [['filled', []]], []);

        $compiled = $result->compile();

        expect($compiled['minItems'])->toBe(1);
    });

    it('defaults to minLength 1 for filled rule when type is not set', function (): void {
        $parser = new AdditionalConstraintParser();
        $schema = FluentSchema::make();

        $result = $parser('field', $schema, [['filled', []]], []);

        $compiled = $result->compile();

        expect($compiled['minLength'])->toBe(1);
    });

    it('sets uniqueItems for distinct rule', function (): void {
        $parser = new AdditionalConstraintParser();
        $schema = FluentSchema::make();

        $result = $parser('items', $schema, [['distinct', []]], []);

        $compiled = $result->compile();

        expect($compiled['uniqueItems'])->toBeTrue();
    });

    it('sets enum for extensions rule', function (): void {
        $parser = new AdditionalConstraintParser();
        $schema = FluentSchema::make();

        $result = $parser('file', $schema, [['extensions', ['jpg', 'png', 'gif']]], []);

        $compiled = $result->compile();

        expect($compiled['enum'])->toBe(['jpg', 'png', 'gif']);
    });

    it('does not modify schema for unrelated rules', function (): void {
        $parser = new AdditionalConstraintParser();
        $schema = FluentSchema::make();

        $result = $parser('field', $schema, [['required', []], ['string', []]], []);

        $compiled = $result->compile();

        expect($compiled)->not->toHaveKey('format')
            ->and($compiled)->not->toHaveKey('minLength')
            ->and($compiled)->not->toHaveKey('uniqueItems')
            ->and($compiled)->not->toHaveKey('enum');
    });
})->covers(AdditionalConstraintParser::class);
