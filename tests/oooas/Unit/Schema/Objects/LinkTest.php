<?php

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Link\Link;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Server\Server;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\RuntimeExpression\Response\ResponseBodyExpression;
use Webmozart\Assert\InvalidArgumentException;

describe(class_basename(Link::class), function (): void {
    it('can be created with no parameters', function (): void {
        $link = Link::create();

        expect($link->compile())->toBeEmpty();
    });

    it('can be created with operationRef', function (): void {
        $link = Link::create()
            ->operationRef('testRef')
            ->description('Some description');

        expect($link->compile())->toBe([
            'operationRef' => 'testRef',
            'description' => 'Some description',
        ]);
    });

    it('can be created with operationId', function (): void {
        $server = Server::default();
        $link = Link::create()
            ->operationId('testId')
            ->description('Some description')
            ->server($server);

        expect($link->compile())->toBe([
            'operationId' => 'testId',
            'description' => 'Some description',
            'server' => $server->compile(),
        ]);
    });

    it('throws exception when setting operationId after operationRef', function (): void {
        $link = Link::create()->operationRef('testRef');

        expect(fn () => $link->operationId('testId'))
            ->toThrow(InvalidArgumentException::class, 'operationId and operationRef fields are mutually exclusive.');
    });

    it('throws exception when setting operationRef after operationId', function (): void {
        $link = Link::create()->operationId('testId');

        expect(fn () => $link->operationRef('testRef'))
            ->toThrow(InvalidArgumentException::class, 'operationRef and operationId fields are mutually exclusive.');
    });

    it('can be created with parameters', function (): void {
        $link = Link::create()
            ->operationId('getUserById')
            ->parameters([
                'userId' => '$response.body#/id',
                'name' => 'John',
            ]);

        expect($link->compile())->toBe([
            'operationId' => 'getUserById',
            'parameters' => [
                'userId' => '$response.body#/id',
                'name' => 'John',
            ],
        ]);
    });

    it('can be created with requestBody', function (): void {
        $link = Link::create()
            ->operationId('createUser')
            ->requestBody(['name' => 'John', 'email' => 'john@example.com']);

        expect($link->compile())->toBe([
            'operationId' => 'createUser',
            'requestBody' => ['name' => 'John', 'email' => 'john@example.com'],
        ]);
    });

    it('can be created with parameters and requestBody', function (): void {
        $link = Link::create()
            ->operationRef('#/paths/~1users~1{userId}/put')
            ->parameters(['userId' => '$response.body#/id'])
            ->requestBody('$response.body')
            ->description('Update the user');

        expect($link->compile())->toBe([
            'operationRef' => '#/paths/~1users~1{userId}/put',
            'parameters' => ['userId' => '$response.body#/id'],
            'requestBody' => '$response.body',
            'description' => 'Update the user',
        ]);
    });

    it('excludes empty parameters array', function (): void {
        $link = Link::create()
            ->operationId('testId')
            ->parameters([]);

        expect($link->compile())->toBe([
            'operationId' => 'testId',
        ]);
    });

    it('can be created with RuntimeExpressionAbstract in parameters', function (): void {
        $expression = ResponseBodyExpression::create('/id');

        $link = Link::create()
            ->operationId('getUserById')
            ->parameters([
                'userId' => $expression,
                'name' => 'John',
            ]);

        expect($link->compile())->toBe([
            'operationId' => 'getUserById',
            'parameters' => [
                'userId' => '$response.body#/id',
                'name' => 'John',
            ],
        ]);
    });

    it('can be created with RuntimeExpressionAbstract as requestBody', function (): void {
        $expression = ResponseBodyExpression::create();

        $link = Link::create()
            ->operationId('createUser')
            ->requestBody($expression);

        expect($link->compile())->toBe([
            'operationId' => 'createUser',
            'requestBody' => '$response.body',
        ]);
    });

    it('can mix RuntimeExpressionAbstract and literals in parameters', function (): void {
        $expression = ResponseBodyExpression::create('/userId');

        $link = Link::create()
            ->operationRef('#/paths/~1users~1{userId}/put')
            ->parameters([
                'userId' => $expression,
                'status' => 'active',
                'count' => 42,
            ])
            ->requestBody(ResponseBodyExpression::create('/data'));

        expect($link->compile())->toBe([
            'operationRef' => '#/paths/~1users~1{userId}/put',
            'parameters' => [
                'userId' => '$response.body#/userId',
                'status' => 'active',
                'count' => 42,
            ],
            'requestBody' => '$response.body#/data',
        ]);
    });
})->covers(Link::class);
