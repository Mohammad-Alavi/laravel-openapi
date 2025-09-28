<?php

namespace Workbench\App\Petstore;

use MohammadAlavi\LaravelOpenApi\Attributes\Collection;
use MohammadAlavi\LaravelOpenApi\Attributes\Operation;
use MohammadAlavi\LaravelOpenApi\Attributes\PathItem;
use MohammadAlavi\LaravelOpenApi\Generator;
use Workbench\App\Petstore\Factories\Responses\MultiResponseMixedWithReusable;
use Workbench\App\Petstore\Factories\Responses\SingleResponse;
use Workbench\App\Petstore\Factories\Responses\SingleResponseUsingReusable;
use Workbench\App\Petstore\Parameters\ListPetsParameters;
use Workbench\App\Petstore\Security\TestComplexMultiSecurityFactory;
use Workbench\App\Petstore\Security\TestSimpleMultiSecurityFactory;
use Workbench\App\Petstore\Security\TestSingleHTTPBearerSchemeSecurityFactory;
use Workbench\App\Petstore\Tags\AnotherPetTag;
use Workbench\App\Petstore\Tags\PetTag;

#[PathItem]
#[Collection(Generator::COLLECTION_DEFAULT)]
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
