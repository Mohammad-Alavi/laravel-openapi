<?php

use MohammadAlavi\LaravelOpenApi\Attributes\Operation;
use Tests\Doubles\Stubs\Attributes\TestSecuritySchemeFactory;

describe('Operation', function (): void {
    it('can be created with no parameters', function (): void {
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

    it('can be created with all parameters', function (): void {
        $operation = new Operation(
            id: 'id',
            tags: 'tags',
            security: TestSecuritySchemeFactory::class,
            method: 'method',
            servers: 'servers',
            summary: 'summary',
            description: 'description',
            deprecated: true,
        );

        expect($operation->id)->toBe('id')
            ->and($operation->tags)->toBe('tags')
            ->and($operation->security)->toBe(TestSecuritySchemeFactory::class)
            ->and($operation->method)->toBe('method')
            ->and($operation->servers)->toBe('servers')
            ->and($operation->summary)->toBe('summary')
            ->and($operation->description)->toBe('description')
            ->and($operation->deprecated)->toBeTrue();
    });
})->covers(Operation::class);
