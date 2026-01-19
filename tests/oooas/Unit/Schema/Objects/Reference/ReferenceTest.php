<?php

namespace Tests\oooas\Unit\Schema\Objects\Reference;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Reference\Reference;

describe(class_basename(Reference::class), function (): void {
    describe('create', function (): void {
        it('can create reference with custom ref', function (): void {
            $reference = Reference::create('#/custom/path');

            expect($reference->compile())->toBe([
                '$ref' => '#/custom/path',
            ]);
        });

        it('can add summary to reference', function (): void {
            $reference = Reference::create('#/components/schemas/Pet')
                ->summary('A pet in the store');

            expect($reference->compile())->toBe([
                '$ref' => '#/components/schemas/Pet',
                'summary' => 'A pet in the store',
            ]);
        });

        it('can add description to reference', function (): void {
            $reference = Reference::create('#/components/schemas/Pet')
                ->description('Detailed description of a pet');

            expect($reference->compile())->toBe([
                '$ref' => '#/components/schemas/Pet',
                'description' => 'Detailed description of a pet',
            ]);
        });

        it('can add both summary and description', function (): void {
            $reference = Reference::create('#/components/schemas/Pet')
                ->summary('A pet')
                ->description('A detailed pet description');

            $compiled = $reference->compile();

            expect($compiled)->toHaveKeys(['$ref', 'summary', 'description'])
                ->and($compiled['summary'])->toBe('A pet')
                ->and($compiled['description'])->toBe('A detailed pet description');
        });
    });

    describe('convenience factory methods', function (): void {
        it('can create schema reference', function (): void {
            $reference = Reference::schema('Pet');

            expect($reference->compile())->toBe([
                '$ref' => '#/components/schemas/Pet',
            ]);
        });

        it('can create response reference', function (): void {
            $reference = Reference::response('NotFound');

            expect($reference->compile())->toBe([
                '$ref' => '#/components/responses/NotFound',
            ]);
        });

        it('can create parameter reference', function (): void {
            $reference = Reference::parameter('PageLimit');

            expect($reference->compile())->toBe([
                '$ref' => '#/components/parameters/PageLimit',
            ]);
        });

        it('can create requestBody reference', function (): void {
            $reference = Reference::requestBody('CreateUser');

            expect($reference->compile())->toBe([
                '$ref' => '#/components/requestBodies/CreateUser',
            ]);
        });

        it('can create header reference', function (): void {
            $reference = Reference::header('X-Rate-Limit');

            expect($reference->compile())->toBe([
                '$ref' => '#/components/headers/X-Rate-Limit',
            ]);
        });

        it('can create securityScheme reference', function (): void {
            $reference = Reference::securityScheme('BearerAuth');

            expect($reference->compile())->toBe([
                '$ref' => '#/components/securitySchemes/BearerAuth',
            ]);
        });

        it('can create link reference', function (): void {
            $reference = Reference::link('GetUserById');

            expect($reference->compile())->toBe([
                '$ref' => '#/components/links/GetUserById',
            ]);
        });

        it('can create callback reference', function (): void {
            $reference = Reference::callback('WebhookCallback');

            expect($reference->compile())->toBe([
                '$ref' => '#/components/callbacks/WebhookCallback',
            ]);
        });

        it('can create pathItem reference', function (): void {
            $reference = Reference::pathItem('SharedPathItem');

            expect($reference->compile())->toBe([
                '$ref' => '#/components/pathItems/SharedPathItem',
            ]);
        });

        it('can create example reference', function (): void {
            $reference = Reference::example('PetExample');

            expect($reference->compile())->toBe([
                '$ref' => '#/components/examples/PetExample',
            ]);
        });

        it('convenience methods can be combined with summary and description', function (): void {
            $reference = Reference::schema('Pet')
                ->summary('Pet schema reference')
                ->description('References the Pet schema defined in components');

            $compiled = $reference->compile();

            expect($compiled['$ref'])->toBe('#/components/schemas/Pet')
                ->and($compiled['summary'])->toBe('Pet schema reference')
                ->and($compiled['description'])->toBe('References the Pet schema defined in components');
        });

        it('rejects invalid component names', function (): void {
            expect(fn () => Reference::schema('invalid/name'))->toThrow(\InvalidArgumentException::class)
                ->and(fn () => Reference::response('name with spaces'))->toThrow(\InvalidArgumentException::class)
                ->and(fn () => Reference::parameter('name#hash'))->toThrow(\InvalidArgumentException::class)
                ->and(fn () => Reference::requestBody(''))->toThrow(\InvalidArgumentException::class);
        });
    });
})->covers(Reference::class);
