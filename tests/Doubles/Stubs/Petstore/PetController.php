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
use Tests\Doubles\Stubs\Petstore\Security\ExampleComplexMultiSecurityRequirementSecurity;
use Tests\Doubles\Stubs\Petstore\Security\ExampleSimpleMultiSecurityRequirementSecurity;
use Tests\Doubles\Stubs\Petstore\Security\ExampleSingleSecurityRequirementSecurity;
use Tests\Doubles\Stubs\Petstore\Tags\AnotherPetTag;
use Tests\Doubles\Stubs\Petstore\Tags\PetTag;

#[PathItem]
class PetController
{
    #[Operation(
        id: 'listPets',
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
        id: 'multiPetTag',
        tags: [PetTag::class, AnotherPetTag::class],
        security: ExampleSingleSecurityRequirementSecurity::class,
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
        id: 'nestedSecurityFirstTest',
        tags: [PetTag::class],
        security: ExampleSimpleMultiSecurityRequirementSecurity::class,
        summary: 'List all pets.',
        description: 'List all pets from the database.',
    )]
    #[Parameters(ListPetsParameters::class)]
    #[Responses(SingleResponse::class)]
    public function nestedSecurityFirst(): void
    {
    }

    #[Operation(
        id: 'nestedSecuritySecondTest',
        tags: AnotherPetTag::class,
        security: ExampleComplexMultiSecurityRequirementSecurity::class,
        summary: 'List all pets.',
        description: 'List all pets from the database.',
        deprecated: null,
    )]
    public function nestedSecuritySecond(): void
    {
    }
}
