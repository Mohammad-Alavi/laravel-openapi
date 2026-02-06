<?php

namespace Tests\oooas\Unit\Schema\Objects\Security\SecurityScheme;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\OAuth\Scope;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\OAuth\ScopeCollection;
use Tests\oooas\Support\Factories\Scopes\AdminScopeFactory;
use Tests\oooas\Support\Factories\Scopes\ApiDeleteScopeFactory;
use Tests\oooas\Support\Factories\Scopes\ApiReadScopeFactory;
use Tests\oooas\Support\Factories\Scopes\ApiWriteScopeFactory;
use Tests\oooas\Support\Factories\Scopes\DeleteScopeFactory;
use Tests\oooas\Support\Factories\Scopes\ReadScopeFactory;
use Tests\oooas\Support\Factories\Scopes\WriteScopeFactory;

describe(class_basename(Scope::class), function (): void {
    it('can be created with name and description', function (): void {
        $scope = Scope::create('read:users', 'Read user data');

        expect($scope->name())->toBe('read:users')
            ->and($scope->description())->toBe('Read user data');
    });

    it('can check equality by name', function (): void {
        $scope1 = Scope::create('read:users', 'Read user data');
        $scope2 = Scope::create('read:users', 'Different description');
        $scope3 = Scope::create('write:users', 'Write user data');

        expect($scope1->equals($scope2))->toBeTrue()
            ->and($scope1->equals($scope3))->toBeFalse();
    });
})->covers(Scope::class);

describe(class_basename(ScopeCollection::class), function (): void {
    it('can be created empty', function (): void {
        $collection = ScopeCollection::create();

        expect($collection->all())->toBeEmpty()
            ->and($collection->compile())->toBe([]);
    });

    it('can be created with scope factories', function (): void {
        $collection = ScopeCollection::create(
            ReadScopeFactory::create(),
            WriteScopeFactory::create(),
        );

        expect($collection->all())->toHaveCount(2)
            ->and($collection->compile())->toBe([
                'read' => 'Read access',
                'write' => 'Write access',
            ]);
    });

    it('can add multiple scopes', function (): void {
        $collection = ScopeCollection::create(
            ApiReadScopeFactory::create(),
            ApiWriteScopeFactory::create(),
            ApiDeleteScopeFactory::create(),
        );

        expect($collection->all())->toHaveCount(3)
            ->and($collection->compile())->toBe([
                'api:read' => 'Read API',
                'api:write' => 'Write API',
                'api:delete' => 'Delete API',
            ]);
    });

    it('can check if contains a scope', function (): void {
        $collection = ScopeCollection::create(
            ReadScopeFactory::create(),
            WriteScopeFactory::create(),
        );

        $readScope = Scope::create('read', 'Read access');
        $deleteScope = Scope::create('delete', 'Delete access');

        expect($collection->contains($readScope))->toBeTrue()
            ->and($collection->contains($deleteScope))->toBeFalse();
    });

    it('can check if contains all scopes', function (): void {
        $collection = ScopeCollection::create(
            ReadScopeFactory::create(),
            WriteScopeFactory::create(),
            DeleteScopeFactory::create(),
        );

        $readScope = Scope::create('read', 'Read access');
        $writeScope = Scope::create('write', 'Write access');
        $adminScope = Scope::create('admin', 'Admin access');

        expect($collection->containsAll($readScope, $writeScope))->toBeTrue()
            ->and($collection->containsAll($readScope, $adminScope))->toBeFalse();
    });

    it('can merge two collections', function (): void {
        $collection1 = ScopeCollection::create(ReadScopeFactory::create());
        $collection2 = ScopeCollection::create(WriteScopeFactory::create());

        $merged = $collection1->merge($collection2);

        expect($merged->all())->toHaveCount(2)
            ->and($merged->compile())->toBe([
                'read' => 'Read access',
                'write' => 'Write access',
            ]);
    });

    it('can merge multiple collections', function (): void {
        $collection1 = ScopeCollection::create(
            ReadScopeFactory::create(),
            WriteScopeFactory::create(),
        );
        $collection2 = ScopeCollection::create(DeleteScopeFactory::create());
        $collection3 = ScopeCollection::create(AdminScopeFactory::create());

        $merged = $collection1->merge($collection2)->merge($collection3);

        expect($merged->all())->toHaveCount(4);
    });

    it('returns scope factories', function (): void {
        $collection = ScopeCollection::create(ReadScopeFactory::create());

        expect($collection->scopeFactories())->toHaveCount(1);
    });
})->covers(ScopeCollection::class);
