<?php

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\RuntimeExpression\Sources\QueryReference;

describe('QueryReference', function (): void {
    it('can be created with a valid name', function (): void {
        $queryReference = QueryReference::create('filter');

        expect($queryReference->name())->toBe('filter');
        expect($queryReference->toString())->toBe('query.filter');
    });

    it('can be created with a name containing special characters', function (): void {
        $queryReference = QueryReference::create('filter[name]');

        expect($queryReference->name())->toBe('filter[name]');
        expect($queryReference->toString())->toBe('query.filter[name]');
    });

    it('throws an exception for an empty name', function (): void {
        expect(fn (): QueryReference => QueryReference::create(''))->toThrow(
            InvalidArgumentException::class,
            'Name cannot be empty',
        );
    });
})->covers(QueryReference::class);
