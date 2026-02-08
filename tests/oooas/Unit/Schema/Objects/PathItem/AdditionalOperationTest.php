<?php

namespace Tests\oooas\Unit\Schema\Objects\PathItem;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Operation\Operation;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem\Support\AdditionalOperation;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem\Support\AdditionalOperations;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response\Response;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Fields\HTTPStatusCode;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Responses;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Support\ResponseEntry;

describe(class_basename(AdditionalOperation::class), function (): void {
    it('can create with custom HTTP method', function (): void {
        $operation = Operation::create()
            ->operationId('customOp')
            ->responses(
                Responses::create(
                    ResponseEntry::create(
                        HTTPStatusCode::ok(),
                        Response::create()->description('OK'),
                    ),
                ),
            );

        $additionalOp = AdditionalOperation::create('CUSTOM', $operation);

        expect($additionalOp->key())->toBe('CUSTOM')
            ->and($additionalOp->value())->toBe($operation);
    });

    it('preserves method capitalization as specified', function (): void {
        $operation = Operation::create()
            ->operationId('test')
            ->responses(
                Responses::create(
                    ResponseEntry::create(HTTPStatusCode::ok(), Response::create()->description('OK')),
                ),
            );

        $upperCase = AdditionalOperation::create('SUBSCRIBE', $operation);
        $mixedCase = AdditionalOperation::create('WebSocket', $operation);

        $operations = AdditionalOperations::create($upperCase, $mixedCase);

        expect($operations->jsonSerialize())->toHaveKeys(['SUBSCRIBE', 'WebSocket']);
    });

    it('rejects standard HTTP method GET (case insensitive)', function (): void {
        $operation = Operation::create()
            ->operationId('test')
            ->responses(
                Responses::create(
                    ResponseEntry::create(HTTPStatusCode::ok(), Response::create()->description('OK')),
                ),
            );

        expect(fn () => AdditionalOperation::create('GET', $operation))
            ->toThrow(\InvalidArgumentException::class)
            ->and(fn () => AdditionalOperation::create('get', $operation))
            ->toThrow(\InvalidArgumentException::class)
            ->and(fn () => AdditionalOperation::create('Get', $operation))
            ->toThrow(\InvalidArgumentException::class);
    });

    it('rejects standard HTTP method POST (case insensitive)', function (): void {
        $operation = Operation::create()
            ->operationId('test')
            ->responses(
                Responses::create(
                    ResponseEntry::create(HTTPStatusCode::ok(), Response::create()->description('OK')),
                ),
            );

        expect(fn () => AdditionalOperation::create('POST', $operation))
            ->toThrow(\InvalidArgumentException::class)
            ->and(fn () => AdditionalOperation::create('post', $operation))
            ->toThrow(\InvalidArgumentException::class);
    });

    it('rejects all standard HTTP methods', function (string $method): void {
        $operation = Operation::create()
            ->operationId('test')
            ->responses(
                Responses::create(
                    ResponseEntry::create(HTTPStatusCode::ok(), Response::create()->description('OK')),
                ),
            );

        expect(fn () => AdditionalOperation::create($method, $operation))
            ->toThrow(\InvalidArgumentException::class);
    })->with([
        'get',
        'GET',
        'put',
        'PUT',
        'post',
        'POST',
        'delete',
        'DELETE',
        'options',
        'OPTIONS',
        'head',
        'HEAD',
        'patch',
        'PATCH',
        'trace',
        'TRACE',
        'query',
        'QUERY',
    ]);
})->covers(AdditionalOperation::class);

describe(class_basename(AdditionalOperations::class), function (): void {
    it('can create with multiple custom operations', function (): void {
        $operation = Operation::create()
            ->operationId('test')
            ->responses(
                Responses::create(
                    ResponseEntry::create(HTTPStatusCode::ok(), Response::create()->description('OK')),
                ),
            );

        $operations = AdditionalOperations::create(
            AdditionalOperation::create('SUBSCRIBE', $operation),
            AdditionalOperation::create('UNSUBSCRIBE', $operation),
        );

        expect($operations->jsonSerialize())->toHaveKeys(['SUBSCRIBE', 'UNSUBSCRIBE']);
    });
})->covers(AdditionalOperations::class);
