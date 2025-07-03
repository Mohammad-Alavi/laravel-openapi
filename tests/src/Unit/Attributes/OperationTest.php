<?php

use MohammadAlavi\LaravelOpenApi\Attributes\Operation;
use Tests\src\Support\Doubles\Stubs\Attributes\TestSecuritySchemeFactory;

describe(class_basename(Operation::class), function (): void {
    it('can be created with no parameters', function (): void {
        $operation = new Operation();

        expect($operation->operationId)->toBeNull()
            ->and($operation->getTags())->toBeEmpty()
            ->and($operation->security)->toBeNull()
            ->and($operation->getServers())->toBeEmpty()
            ->and($operation->summary)->toBeNull()
            ->and($operation->description)->toBeNull()
            ->and($operation->deprecated)->toBeNull();
    });

    it('can be created with all parameters', function (): void {
        $operation = new Operation(
            tags: 'tags',
            summary: 'summary',
            description: 'description',
            externalDocs: null,
            deprecated: true,
            security: TestSecuritySchemeFactory::class,
            servers: 'servers',
            operationId: 'id',
        );

        expect($operation->operationId)->toBe('id')
            ->and($operation->getTags())->toBe(['tags'])
            ->and($operation->security)->toBe(TestSecuritySchemeFactory::class)
            ->and($operation->getServers())->toBe(['servers'])
            ->and($operation->summary)->toBe('summary')
            ->and($operation->description)->toBe('description')
            ->and($operation->deprecated)->toBeTrue();
    });
})->covers(Operation::class);
