<?php

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Link;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Server\Server;

describe('Link', function (): void {
    it('can be created with no parameters', function (): void {
        $link = Link::create('test');

        expect($link->asArray())->toBeEmpty();
    });

    it('can be created with all parameters', function (): void {
        $server = Server::default();
        $link = Link::create('test')
            ->operationRef('testRef')
            ->operationId('testId')
            ->description('Some descriptions')
            ->server($server);

        expect($link->asArray())->toBe([
            'operationRef' => 'testRef',
            'operationId' => 'testId',
            'description' => 'Some descriptions',
            'server' => $server->asArray(),
        ]);
    });
})->covers(Link::class);
