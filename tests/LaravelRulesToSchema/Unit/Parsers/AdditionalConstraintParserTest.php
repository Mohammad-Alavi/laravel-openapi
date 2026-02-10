<?php

declare(strict_types=1);

use MohammadAlavi\LaravelRulesToSchema\Parsers\AdditionalConstraintParser;
use MohammadAlavi\LaravelRulesToSchema\ValidationRule;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Type;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\LooseFluentDescriptor;

describe(class_basename(AdditionalConstraintParser::class), function (): void {
    it('sets format uri for active_url rule', function (): void {
        $parser = new AdditionalConstraintParser();
        $schema = LooseFluentDescriptor::withoutSchema();

        $result = $parser('website', $schema, [new ValidationRule('active_url')], []);

        $compiled = $result->compile();

        expect($compiled['format'])->toBe('uri');
    });

    it('sets format timezone for timezone rule', function (): void {
        $parser = new AdditionalConstraintParser();
        $schema = LooseFluentDescriptor::withoutSchema();

        $result = $parser('tz', $schema, [new ValidationRule('timezone')], []);

        $compiled = $result->compile();

        expect($compiled['format'])->toBe('timezone');
    });

    it('sets minLength 1 for filled rule on string type', function (): void {
        $parser = new AdditionalConstraintParser();
        $schema = LooseFluentDescriptor::withoutSchema()->type(Type::string());

        $result = $parser('name', $schema, [new ValidationRule('filled')], []);

        $compiled = $result->compile();

        expect($compiled['minLength'])->toBe(1);
    });

    it('sets minItems 1 for filled rule on array type', function (): void {
        $parser = new AdditionalConstraintParser();
        $schema = LooseFluentDescriptor::withoutSchema()->type(Type::array());

        $result = $parser('tags', $schema, [new ValidationRule('filled')], []);

        $compiled = $result->compile();

        expect($compiled['minItems'])->toBe(1);
    });

    it('defaults to minLength 1 for filled rule when type is not set', function (): void {
        $parser = new AdditionalConstraintParser();
        $schema = LooseFluentDescriptor::withoutSchema();

        $result = $parser('field', $schema, [new ValidationRule('filled')], []);

        $compiled = $result->compile();

        expect($compiled['minLength'])->toBe(1);
    });

    it('sets uniqueItems for distinct rule', function (): void {
        $parser = new AdditionalConstraintParser();
        $schema = LooseFluentDescriptor::withoutSchema();

        $result = $parser('items', $schema, [new ValidationRule('distinct')], []);

        $compiled = $result->compile();

        expect($compiled['uniqueItems'])->toBeTrue();
    });

    it('sets enum for extensions rule', function (): void {
        $parser = new AdditionalConstraintParser();
        $schema = LooseFluentDescriptor::withoutSchema();

        $result = $parser('file', $schema, [new ValidationRule('extensions', ['jpg', 'png', 'gif'])], []);

        $compiled = $result->compile();

        expect($compiled['enum'])->toBe(['jpg', 'png', 'gif']);
    });

    it('does not modify schema for unrelated rules', function (): void {
        $parser = new AdditionalConstraintParser();
        $schema = LooseFluentDescriptor::withoutSchema();

        $result = $parser('field', $schema, [new ValidationRule('required'), new ValidationRule('string')], []);

        $compiled = $result->compile();

        expect($compiled)->not->toHaveKey('format')
            ->and($compiled)->not->toHaveKey('minLength')
            ->and($compiled)->not->toHaveKey('uniqueItems')
            ->and($compiled)->not->toHaveKey('enum');
    });
})->covers(AdditionalConstraintParser::class);
