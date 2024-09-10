<?php

use MohammadAlavi\LaravelOpenApi\Attributes\Operation;
use Tests\Unit\Attributes\Stubs\SecuritySchemeFactoryInvalidStub;
use Tests\Unit\Attributes\Stubs\SecuritySchemeFactoryStub;

describe('Operation', function () {
    it('can be instantiated with no parameters', function () {
        $operation = new Operation();
        expect($operation->id)->toBeNull()
            ->and($operation->tags)->toBeNull()
            ->and($operation->security)->toBeNull()
            ->and($operation->method)->toBeNull()
            ->and($operation->servers)->toBeNull()
            ->and($operation->summary)->toBeNull()
            ->and($operation->description)->toBeNull()
            ->and($operation->deprecated)->toBeNull();
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
        expect($operation->id)->toBe('id')
            ->and($operation->tags)->toBe('tags')
            ->and($operation->security)->toBe(SecuritySchemeFactoryStub::class)
            ->and($operation->method)->toBe('method')
            ->and($operation->servers)->toBe('servers')
            ->and($operation->summary)->toBe('summary')
            ->and($operation->description)->toBe('description')
            ->and($operation->deprecated)->toBeTrue();
    });

    it('doesnt accept invalid security combinations', function (string|array $security) {
        $this->expectException(InvalidArgumentException::class);
        new Operation(security: $security);
    })->with([
        [
            '',
        ],
        [
            [[]],
        ],
        [
            [''],
        ],
        [
            [['']],
        ],
        [
            ['', []],
        ],
        [
            ['', ['']],
        ],
        [
            ['invalid', 'input'],
        ],
        [
            ['nested', ['invalid']],
        ],
        [
            [null],
        ],
        [
            [null, []],
        ],
        [
            [null, '', []],
        ],
        [
            ['non-empty', [null, '']],
        ],
        [
            ['InvalidSecurity', SecuritySchemeFactoryStub::class],
        ],
    ]);

    it('can remove top level security', function () {
        $operation = new Operation(security: []);
        expect($operation->security)->toBe([]);
    });

    it('can use top level security', function () {
        $operation = new Operation(security: null);
        expect($operation->security)->toBeNull();
    });

    it('uses top level security by default', function () {
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

    it('throws an exception when security is not an instance of SecuritySchemeFactory', function () {
        $this->expectException(InvalidArgumentException::class);
        new Operation(security: SecuritySchemeFactoryInvalidStub::class);
    });
})->covers(Operation::class);
