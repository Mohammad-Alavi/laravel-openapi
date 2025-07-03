<?php

namespace Tests\src\Support\Doubles\Stubs\Petstore;

use MohammadAlavi\LaravelOpenApi\Attributes\Operation;
use MohammadAlavi\LaravelOpenApi\Attributes\PathItem;
use Tests\src\Support\Doubles\Stubs\Petstore\Factories\Responses\MultiResponseMixedWithReusable;
use Tests\src\Support\Doubles\Stubs\Petstore\Factories\Responses\SingleResponse;
use Tests\src\Support\Doubles\Stubs\Petstore\Factories\Responses\SingleResponseUsingReusable;
use Tests\src\Support\Doubles\Stubs\Petstore\Parameters\ListPetsParameters;
use Tests\src\Support\Doubles\Stubs\Petstore\Security\TestComplexMultiSecurityFactory;
use Tests\src\Support\Doubles\Stubs\Petstore\Security\TestSimpleMultiSecurityFactory;
use Tests\src\Support\Doubles\Stubs\Petstore\Security\TestSingleHTTPBearerSchemeSecurityFactory;
use Tests\src\Support\Doubles\Stubs\Petstore\Tags\AnotherPetTag;
use Tests\src\Support\Doubles\Stubs\Petstore\Tags\PetTag;

#[PathItem]
class PetController
{
    #[Operation(
        tags: PetTag::class,
        summary: 'List all pets.',
        description: 'List all pets from the database.',
        parameters: ListPetsParameters::class,
        responses: SingleResponseUsingReusable::class,
        deprecated: true,
        operationId: 'listPets',
    )]
    public function index(): void
    {
    }

    #[Operation(
        tags: [PetTag::class, AnotherPetTag::class],
        summary: 'List all pets.',
        description: 'List all pets from the database.',
        parameters: ListPetsParameters::class,
        responses: MultiResponseMixedWithReusable::class,
        deprecated: false,
        security: TestSingleHTTPBearerSchemeSecurityFactory::class,
        operationId: 'multiPetTag',
    )]
    public function multiTag(): void
    {
    }

    #[Operation(
        tags: [PetTag::class],
        summary: 'List all pets.',
        description: 'List all pets from the database.',
        parameters: ListPetsParameters::class,
        responses: SingleResponse::class,
        deprecated: null,
        security: TestSimpleMultiSecurityFactory::class,
        operationId: 'nestedSecurityFirstTest',
    )]
    public function nestedSecurity(): void
    {
    }

    #[Operation(
        tags: AnotherPetTag::class,
        summary: 'List all pets.',
        description: 'List all pets from the database.',
        security: TestComplexMultiSecurityFactory::class,
        operationId: 'nestedSecuritySecondTest',
    )]
    public function anotherNestedSecurity(): void
    {
    }
}
