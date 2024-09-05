<?php

use MohammadAlavi\LaravelOpenApi\Attributes\Operation;
use Tests\Unit\Attributes\Stubs\SecuritySchemeFactoryInvalidStub;
use Tests\Unit\Attributes\Stubs\SecuritySchemeFactoryStub;

describe('Operation', function () {
    it('can be instantiated with no parameters', function () {
        $operation = new Operation();
        expect($operation->id)->toBeNull();
        expect($operation->tags)->toBeNull();
        expect($operation->security)->toBeNull();
        expect($operation->method)->toBeNull();
        expect($operation->servers)->toBeNull();
        expect($operation->summary)->toBeNull();
        expect($operation->description)->toBeNull();
        expect($operation->deprecated)->toBeNull();
    });

    it('can can be instantiated properly', function () {
        $operation = new Operation(
            id: 'id',
            tags: 'tags',
            security: SecuritySchemeFactoryStub::class,
            method: 'method',
            servers: 'servers',
            summary: 'summary',
            description: 'description',
            deprecated: true,
        );
        expect($operation->id)->toBe('id');
        expect($operation->tags)->toBe('tags');
        expect($operation->security)->toBe(SecuritySchemeFactoryStub::class);
        expect($operation->method)->toBe('method');
        expect($operation->servers)->toBe('servers');
        expect($operation->summary)->toBe('summary');
        expect($operation->description)->toBe('description');
        expect($operation->deprecated)->toBeTrue();
    });

    it('can have no security', function () {
        $operation = new Operation();
        expect($operation->security)->toBeNull();
    });

    it('can be instantiated with a class FQN security', function () {
        $operation = new Operation(security: SecuritySchemeFactoryStub::class);
        expect($operation->security)->toBe(SecuritySchemeFactoryStub::class);
    });

    it('can be instantiated with an array of class FQN securities', function () {
        $operation = new Operation(security: [SecuritySchemeFactoryStub::class]);
        expect($operation->security)->toBe([SecuritySchemeFactoryStub::class]);
    });

    it('can be instantiated with an array of arrays of class FQN securities', function () {
        $operation = new Operation(security: [[SecuritySchemeFactoryStub::class]]);
        expect($operation->security)->toBe([[SecuritySchemeFactoryStub::class]]);
    });

    it('can be instantiated with a combination of class FQN and array of class FQN securities', function () {
        $operation = new Operation(security: [SecuritySchemeFactoryStub::class, [SecuritySchemeFactoryStub::class]]);
        expect($operation->security)->toBe([SecuritySchemeFactoryStub::class, [SecuritySchemeFactoryStub::class]]);
    });

    it('throws an exception when an invalid security is passed', function () {
        $this->expectException(InvalidArgumentException::class);
        new Operation(security: 'InvalidSecurity');
    });

    it('throws an exception when an invalid security is passed in an array', function () {
        $this->expectException(InvalidArgumentException::class);
        new Operation(security: ['InvalidSecurity']);
    });

    it('throws an exception when an invalid security is mixed with valid ones', function () {
        $this->expectException(InvalidArgumentException::class);
        new Operation(security: ['InvalidSecurity', SecuritySchemeFactoryStub::class]);
    });

    it('throws an exception when security is not an instance of SecuritySchemeFactory', function () {
        $this->expectException(InvalidArgumentException::class);
        new Operation(security: SecuritySchemeFactoryInvalidStub::class);
    });
})->covers(Operation::class);