<?php

use Illuminate\Validation\Rules\NotIn;
use MohammadAlavi\LaravelRulesToSchema\NestedRuleset;
use MohammadAlavi\LaravelRulesToSchema\Parsers\NotInParser;
use MohammadAlavi\LaravelRulesToSchema\ValidationRule;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\LooseFluentDescriptor;

describe(class_basename(NotInParser::class), function (): void {
    it('sets not enum for not_in string rule', function (): void {
        $parser = new NotInParser();
        $schema = LooseFluentDescriptor::withoutSchema();

        $result = $parser('field', $schema, [new ValidationRule('not_in', ['foo', 'bar', 'baz'])], new NestedRuleset());

        $compiled = $result->schema()->compile();

        expect($compiled['not'])->toBe(['enum' => ['foo', 'bar', 'baz']]);
    });

    it('sets not enum for NotIn rule object', function (): void {
        $parser = new NotInParser();
        $schema = LooseFluentDescriptor::withoutSchema();
        $rule = new NotIn(['alpha', 'beta']);

        $result = $parser('field', $schema, [new ValidationRule($rule)], new NestedRuleset());

        $compiled = $result->schema()->compile();

        expect($compiled['not'])->toBe(['enum' => ['alpha', 'beta']]);
    });

    it('does not modify schema for non-not_in rules', function (): void {
        $parser = new NotInParser();
        $schema = LooseFluentDescriptor::withoutSchema();

        $result = $parser('field', $schema, [new ValidationRule('required'), new ValidationRule('string')], new NestedRuleset());

        $compiled = $result->schema()->compile();

        expect($compiled)->not->toHaveKey('not');
    });

    it('handles single value in not_in', function (): void {
        $parser = new NotInParser();
        $schema = LooseFluentDescriptor::withoutSchema();

        $result = $parser('field', $schema, [new ValidationRule('not_in', ['only'])], new NestedRuleset());

        $compiled = $result->schema()->compile();

        expect($compiled['not'])->toBe(['enum' => ['only']]);
    });
})->covers(NotInParser::class);
