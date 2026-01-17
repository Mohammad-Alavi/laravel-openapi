<?php

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Link\Link;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Server\Server;
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
})->covers(Link::class);
