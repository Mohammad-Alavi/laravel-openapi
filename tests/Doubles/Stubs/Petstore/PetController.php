<?php

namespace Tests\Doubles\Stubs\Petstore;

use MohammadAlavi\LaravelOpenApi\Attributes\Operation;
use MohammadAlavi\LaravelOpenApi\Attributes\Parameters;
use MohammadAlavi\LaravelOpenApi\Attributes\PathItem;
use MohammadAlavi\LaravelOpenApi\Attributes\Responses;
use Tests\Doubles\Stubs\Petstore\Factories\Responses\MultiResponseMixedWithReusable;
use Tests\Doubles\Stubs\Petstore\Factories\Responses\SingleResponse;
use Tests\Doubles\Stubs\Petstore\Factories\Responses\SingleResponseUsingReusable;
use Tests\Doubles\Stubs\Petstore\Parameters\ListPetsParameters;
use Tests\Doubles\Stubs\Petstore\Security\TestComplexMultiSecurityFactory;
use Tests\Doubles\Stubs\Petstore\Security\TestSimpleMultiSecurityFactory;
use Tests\Doubles\Stubs\Petstore\Security\TestSingleHTTPBearerSchemeSecurityFactory;
use Tests\Doubles\Stubs\Petstore\Tags\AnotherPetTag;
use Tests\Doubles\Stubs\Petstore\Tags\PetTag;

#[PathItem]
class PetController
{
    #[Operation(
        operationId: 'listPets',
        tags: PetTag::class,
        summary: 'List all pets.',
        description: 'List all pets from the database.',
        deprecated: true,
    )]
    #[Parameters(ListPetsParameters::class)]
    #[Responses(SingleResponseUsingReusable::class)]
    public function index(): void
    {
    }

    #[Operation(
        operationId: 'multiPetTag',
        tags: [PetTag::class, AnotherPetTag::class],
        security: TestSingleHTTPBearerSchemeSecurityFactory::class,
        summary: 'List all pets.',
        description: 'List all pets from the database.',
        deprecated: false,
    )]
    #[Parameters(ListPetsParameters::class)]
    #[Responses(MultiResponseMixedWithReusable::class)]
    public function multiPetTag(): void
    {
    }

    #[Operation(
        operationId: 'nestedSecurityFirstTest',
        tags: [PetTag::class],
        security: TestSimpleMultiSecurityFactory::class,
        summary: 'List all pets.',
        description: 'List all pets from the database.',
    )]
    #[Parameters(ListPetsParameters::class)]
    #[Responses(SingleResponse::class)]
    public function nestedSecurityFirst(): void
    {
    }

    #[Operation(
        operationId: 'nestedSecuritySecondTest',
        tags: AnotherPetTag::class,
        security: TestComplexMultiSecurityFactory::class,
        summary: 'List all pets.',
        description: 'List all pets from the database.',
        deprecated: null,
    )]
    public function nestedSecuritySecond(): void
    {
    }
}
