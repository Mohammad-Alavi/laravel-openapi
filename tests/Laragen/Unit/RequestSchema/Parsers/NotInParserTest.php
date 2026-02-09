<?php

declare(strict_types=1);

use FluentJsonSchema\FluentSchema;
use Illuminate\Validation\Rules\NotIn;
use MohammadAlavi\Laragen\RequestSchema\Parsers\NotInParser;

describe(class_basename(NotInParser::class), function (): void {
    it('sets not enum for not_in string rule', function (): void {
        $parser = new NotInParser();
        $schema = FluentSchema::make();

        $result = $parser('field', $schema, [['not_in', ['foo', 'bar', 'baz']]], []);

        $compiled = $result->compile();

        expect($compiled['not'])->toBe(['enum' => ['foo', 'bar', 'baz']]);
    });

    it('sets not enum for NotIn rule object', function (): void {
        $parser = new NotInParser();
        $schema = FluentSchema::make();
        $rule = new NotIn(['alpha', 'beta']);

        $result = $parser('field', $schema, [[$rule, []]], []);

        $compiled = $result->compile();

        expect($compiled['not'])->toBe(['enum' => ['alpha', 'beta']]);
    });

    it('does not modify schema for non-not_in rules', function (): void {
        $parser = new NotInParser();
        $schema = FluentSchema::make();

        $result = $parser('field', $schema, [['required', []], ['string', []]], []);

        $compiled = $result->compile();

        expect($compiled)->not->toHaveKey('not');
    });

    it('handles single value in not_in', function (): void {
        $parser = new NotInParser();
        $schema = FluentSchema::make();

        $result = $parser('field', $schema, [['not_in', ['only']]], []);

        $compiled = $result->compile();

        expect($compiled['not'])->toBe(['enum' => ['only']]);
    });
})->covers(NotInParser::class);
