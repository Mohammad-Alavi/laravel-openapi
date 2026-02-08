<?php

declare(strict_types=1);

use MohammadAlavi\Laragen\ResponseSchema\JsonResourceAnalyzer;
use MohammadAlavi\Laragen\ResponseSchema\ResourceField;
use Tests\Laragen\Support\Doubles\Resources\PostResource;
use Tests\Laragen\Support\Doubles\Resources\UnwrappedResource;
use Tests\Laragen\Support\Doubles\Resources\UserResource;

describe(class_basename(JsonResourceAnalyzer::class), function (): void {
    it('extracts fields from simple resource', function (): void {
        $analyzer = new JsonResourceAnalyzer();
        $fields = $analyzer->analyze(UserResource::class);

        expect($fields)->toHaveCount(5);

        $fieldNames = array_map(static fn (ResourceField $f): string => $f->name, $fields);

        expect($fieldNames)->toBe(['id', 'name', 'email', 'type', 'is_active']);
    });

    it('detects model property references', function (): void {
        $analyzer = new JsonResourceAnalyzer();
        $fields = $analyzer->analyze(UserResource::class);

        $idField = $fields[0];

        expect($idField->name)->toBe('id')
            ->and($idField->isModelProperty)->toBeTrue()
            ->and($idField->modelProperty)->toBe('id');
    });

    it('detects literal values', function (): void {
        $analyzer = new JsonResourceAnalyzer();
        $fields = $analyzer->analyze(UserResource::class);

        $typeField = collect($fields)->firstWhere('name', 'type');

        expect($typeField)->not->toBeNull()
            ->and($typeField->isLiteral)->toBeTrue()
            ->and($typeField->literalValue)->toBe('user');
    });

    it('detects whenLoaded relationships', function (): void {
        $analyzer = new JsonResourceAnalyzer();
        $fields = $analyzer->analyze(PostResource::class);

        $authorField = collect($fields)->firstWhere('name', 'author');

        expect($authorField)->not->toBeNull()
            ->and($authorField->isRelationship)->toBeTrue()
            ->and($authorField->resourceClass)->toBe(UserResource::class);
    });

    it('detects conditional fields', function (): void {
        $analyzer = new JsonResourceAnalyzer();
        $fields = $analyzer->analyze(PostResource::class);

        $tagsField = collect($fields)->firstWhere('name', 'tags');

        expect($tagsField)->not->toBeNull()
            ->and($tagsField->isConditional)->toBeTrue();
    });

    it('detects wrap property', function (): void {
        $analyzer = new JsonResourceAnalyzer();

        expect($analyzer->getWrapKey(UserResource::class))->toBe('data')
            ->and($analyzer->getWrapKey(UnwrappedResource::class))->toBeNull();
    });
})->covers(JsonResourceAnalyzer::class);
