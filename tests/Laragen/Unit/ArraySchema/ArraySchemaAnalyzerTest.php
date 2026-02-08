<?php

declare(strict_types=1);

use MohammadAlavi\Laragen\ArraySchema\ArrayField;
use MohammadAlavi\Laragen\ArraySchema\ArraySchemaAnalyzer;
use Tests\Laragen\Support\Doubles\Resources\PostResource;
use Tests\Laragen\Support\Doubles\Resources\ResourceWithCollection;
use Tests\Laragen\Support\Doubles\Resources\ResourceWithConditionals;
use Tests\Laragen\Support\Doubles\Resources\ResourceWithLiterals;
use Tests\Laragen\Support\Doubles\Resources\ResourceWithMerge;
use Tests\Laragen\Support\Doubles\Resources\ResourceWithMethodChains;
use Tests\Laragen\Support\Doubles\Resources\ResourceWithTernary;
use Tests\Laragen\Support\Doubles\Resources\UserResource;

describe(class_basename(ArraySchemaAnalyzer::class), function (): void {
    it('analyzes any class method that returns an array', function (): void {
        $analyzer = new ArraySchemaAnalyzer();
        $fields = $analyzer->analyzeMethod(UserResource::class, 'toArray');

        $fieldNames = array_map(static fn (ArrayField $f): string => $f->name, $fields);

        expect($fieldNames)->toBe(['id', 'name', 'email', 'type', 'is_active']);
    });

    it('detects model property references', function (): void {
        $analyzer = new ArraySchemaAnalyzer();
        $fields = $analyzer->analyzeMethod(UserResource::class, 'toArray');

        $idField = $fields[0];

        expect($idField->isModelProperty)->toBeTrue()
            ->and($idField->modelProperty)->toBe('id');
    });

    it('detects all literal types including bool and null', function (): void {
        $analyzer = new ArraySchemaAnalyzer();
        $fields = $analyzer->analyzeMethod(ResourceWithLiterals::class, 'toArray');

        $verified = collect($fields)->firstWhere('name', 'is_verified');
        $banned = collect($fields)->firstWhere('name', 'is_banned');
        $deleted = collect($fields)->firstWhere('name', 'deleted_at');
        $type = collect($fields)->firstWhere('name', 'type');

        expect($verified->isLiteral)->toBeTrue()
            ->and($verified->literalValue)->toBeTrue()
            ->and($banned->isLiteral)->toBeTrue()
            ->and($banned->literalValue)->toBeFalse()
            ->and($deleted->isLiteral)->toBeTrue()
            ->and($deleted->literalValue)->toBeNull()
            ->and($type->isLiteral)->toBeTrue()
            ->and($type->literalValue)->toBe('user');
    });

    it('detects method chains on model properties', function (): void {
        $analyzer = new ArraySchemaAnalyzer();
        $fields = $analyzer->analyzeMethod(ResourceWithMethodChains::class, 'toArray');

        $created = collect($fields)->firstWhere('name', 'created_date');

        expect($created->isModelProperty)->toBeTrue()
            ->and($created->modelProperty)->toBe('created_at');
    });

    it('detects relationships via new Resource()', function (): void {
        $analyzer = new ArraySchemaAnalyzer();
        $fields = $analyzer->analyzeMethod(PostResource::class, 'toArray');

        $author = collect($fields)->firstWhere('name', 'author');

        expect($author->isRelationship)->toBeTrue()
            ->and($author->resourceClass)->toBe(UserResource::class);
    });

    it('detects static collection calls', function (): void {
        $analyzer = new ArraySchemaAnalyzer();
        $fields = $analyzer->analyzeMethod(ResourceWithCollection::class, 'toArray');

        $posts = collect($fields)->firstWhere('name', 'posts');

        expect($posts->isCollection)->toBeTrue()
            ->and($posts->resourceClass)->toBe(PostResource::class);
    });

    it('detects conditional methods', function (): void {
        $analyzer = new ArraySchemaAnalyzer();
        $fields = $analyzer->analyzeMethod(ResourceWithConditionals::class, 'toArray');

        $tags = collect($fields)->firstWhere('name', 'tags');

        expect($tags->isConditional)->toBeTrue();
    });

    it('flattens merge fields into parent', function (): void {
        $analyzer = new ArraySchemaAnalyzer();
        $fields = $analyzer->analyzeMethod(ResourceWithMerge::class, 'toArray');

        $fieldNames = array_map(static fn (ArrayField $f): string => $f->name, $fields);

        expect($fieldNames)->toContain('first_name', 'last_name', 'role', 'permissions', 'settings');
    });

    it('classifies unrecognized expressions as unknown', function (): void {
        $analyzer = new ArraySchemaAnalyzer();
        $fields = $analyzer->analyzeMethod(ResourceWithTernary::class, 'toArray');

        $status = collect($fields)->firstWhere('name', 'status');

        expect($status->isModelProperty)->toBeFalse()
            ->and($status->isLiteral)->toBeFalse()
            ->and($status->isConditional)->toBeFalse()
            ->and($status->isRelationship)->toBeFalse()
            ->and($status->isCollection)->toBeFalse();
    });
})->covers(ArraySchemaAnalyzer::class);
