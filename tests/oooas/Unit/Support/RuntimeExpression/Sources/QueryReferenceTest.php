<?php

use MohammadAlavi\ObjectOrientedOpenAPI\Support\RuntimeExpression\Sources\QueryReference;

describe(class_basename(QueryReference::class), function (): void {
    it('can be created with a valid name', function (): void {
        $queryReference = QueryReference::create('filter');

        expect($queryReference->name())->toBe('filter')
            ->and($queryReference->toString())->toBe('query.filter');
    });

    it('can be created with a name containing special characters', function (): void {
        $queryReference = QueryReference::create('filter[name]');

        expect($queryReference->name())->toBe('filter[name]')
            ->and($queryReference->toString())->toBe('query.filter[name]');
    });

    it('throws an exception for an empty name', function (): void {
        expect(function (): QueryReference {
            return QueryReference::create('');
        })->toThrow(
            InvalidArgumentException::class,
            'Name cannot be empty',
        );
    });
})->covers(QueryReference::class);
