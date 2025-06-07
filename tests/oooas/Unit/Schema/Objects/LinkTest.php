<?php

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Link\Fields\Description;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Link\Fields\OperationId;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Link\Fields\OperationRef;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Link\Link;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Server\Server;

describe('Link', function (): void {
    it('can be created with no parameters', function (): void {
        $link = Link::create();

        expect($link->asArray())->toBeEmpty();
    });

    it('can be created with all parameters', function (): void {
        $server = Server::default();
        $link = Link::create()
            ->operationRef(OperationRef::create('testRef'))
            ->operationId(OperationId::create('testId'))
            ->description(Description::create('Some descriptions'))
            ->server($server);

        expect($link->asArray())->toBe([
            'operationRef' => 'testRef',
            'operationId' => 'testId',
            'description' => 'Some descriptions',
            'server' => $server->asArray(),
        ]);
    });
})->covers(Link::class);
