<?php

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Link\Link;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Server\Server;

describe('Link', function (): void {
    it('can be created with no parameters', function (): void {
        $link = Link::create();

        expect($link->unserializeToArray())->toBeEmpty();
    });

    it('can be created with all parameters', function (): void {
        $server = Server::default();
        $link = Link::create()
            ->operationRef('testRef')
            ->operationId('testId')
            ->description('Some descriptions')
            ->server($server);

        expect($link->unserializeToArray())->toBe([
            'operationRef' => 'testRef',
            'operationId' => 'testId',
            'description' => 'Some descriptions',
            'server' => $server->unserializeToArray(),
        ]);
    });
})->covers(Link::class);
