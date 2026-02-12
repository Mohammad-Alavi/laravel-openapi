<?php

use MohammadAlavi\Laragen\Annotations\DetectedResponseAnnotation;
use MohammadAlavi\Laragen\ResponseSchema\Annotation\AnnotationResponseSchemaBuilder;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Compilable;

describe(class_basename(AnnotationResponseSchemaBuilder::class), function (): void {
    it('builds object schema from JSON response annotation', function (): void {
        $builder = new AnnotationResponseSchemaBuilder();
        $annotations = [
            new DetectedResponseAnnotation(200, '{"id": 1, "name": "John", "is_active": true}'),
        ];

        $schema = $builder->build($annotations);

        expect($schema)->toBeInstanceOf(Compilable::class);

        /** @var Compilable $schema */
        $compiled = $schema->compile();

        expect($compiled)->toHaveKey('type')
            ->and($compiled['type'])->toBe('object')
            ->and($compiled)->toHaveKey('properties')
            ->and($compiled['properties'])->toHaveKey('id')
            ->and($compiled['properties']['id'])->toBe(['type' => 'integer'])
            ->and($compiled['properties'])->toHaveKey('name')
            ->and($compiled['properties']['name'])->toBe(['type' => 'string'])
            ->and($compiled['properties'])->toHaveKey('is_active')
            ->and($compiled['properties']['is_active'])->toBe(['type' => 'boolean']);
    });

    it('uses first 2xx response annotation for schema', function (): void {
        $builder = new AnnotationResponseSchemaBuilder();
        $annotations = [
            new DetectedResponseAnnotation(404, '{"error": "Not found"}'),
            new DetectedResponseAnnotation(200, '{"id": 1}'),
        ];

        $schema = $builder->build($annotations);

        /** @var Compilable $schema */
        $compiled = $schema->compile();

        expect($compiled['properties'])->toHaveKey('id')
            ->and($compiled['properties'])->not->toHaveKey('error');
    });

    it('falls back to first annotation when no 2xx status', function (): void {
        $builder = new AnnotationResponseSchemaBuilder();
        $annotations = [
            new DetectedResponseAnnotation(404, '{"error": "Not found"}'),
        ];

        $schema = $builder->build($annotations);

        /** @var Compilable $schema */
        $compiled = $schema->compile();

        expect($compiled['properties'])->toHaveKey('error');
    });

    it('handles nested objects recursively', function (): void {
        $builder = new AnnotationResponseSchemaBuilder();
        $annotations = [
            new DetectedResponseAnnotation(200, '{"user": {"id": 1, "name": "John"}}'),
        ];

        $schema = $builder->build($annotations);

        /** @var Compilable $schema */
        $compiled = $schema->compile();

        expect($compiled['properties'])->toHaveKey('user')
            ->and($compiled['properties']['user']['type'])->toBe('object')
            ->and($compiled['properties']['user']['properties'])->toHaveKey('id')
            ->and($compiled['properties']['user']['properties'])->toHaveKey('name');
    });

    it('handles arrays with type inference from first element', function (): void {
        $builder = new AnnotationResponseSchemaBuilder();
        $annotations = [
            new DetectedResponseAnnotation(200, '{"tags": ["php", "laravel"]}'),
        ];

        $schema = $builder->build($annotations);

        /** @var Compilable $schema */
        $compiled = $schema->compile();

        expect($compiled['properties'])->toHaveKey('tags')
            ->and($compiled['properties']['tags']['type'])->toBe('array')
            ->and($compiled['properties']['tags']['items'])->toBe(['type' => 'string']);
    });

    it('handles arrays of objects', function (): void {
        $builder = new AnnotationResponseSchemaBuilder();
        $annotations = [
            new DetectedResponseAnnotation(200, '{"users": [{"id": 1, "name": "John"}]}'),
        ];

        $schema = $builder->build($annotations);

        /** @var Compilable $schema */
        $compiled = $schema->compile();

        expect($compiled['properties'])->toHaveKey('users')
            ->and($compiled['properties']['users']['type'])->toBe('array')
            ->and($compiled['properties']['users']['items']['type'])->toBe('object')
            ->and($compiled['properties']['users']['items']['properties'])->toHaveKey('id')
            ->and($compiled['properties']['users']['items']['properties'])->toHaveKey('name');
    });

    it('handles number (float) values', function (): void {
        $builder = new AnnotationResponseSchemaBuilder();
        $annotations = [
            new DetectedResponseAnnotation(200, '{"score": 9.5}'),
        ];

        $schema = $builder->build($annotations);

        /** @var Compilable $schema */
        $compiled = $schema->compile();

        expect($compiled['properties']['score'])->toBe(['type' => 'number']);
    });

    it('handles null values as string type', function (): void {
        $builder = new AnnotationResponseSchemaBuilder();
        $annotations = [
            new DetectedResponseAnnotation(200, '{"value": null}'),
        ];

        $schema = $builder->build($annotations);

        /** @var Compilable $schema */
        $compiled = $schema->compile();

        expect($compiled['properties']['value'])->toBe(['type' => 'string']);
    });

    it('handles empty arrays', function (): void {
        $builder = new AnnotationResponseSchemaBuilder();
        $annotations = [
            new DetectedResponseAnnotation(200, '{"items": []}'),
        ];

        $schema = $builder->build($annotations);

        /** @var Compilable $schema */
        $compiled = $schema->compile();

        expect($compiled['properties'])->toHaveKey('items')
            ->and($compiled['properties']['items']['type'])->toBe('array');
    });
})->covers(AnnotationResponseSchemaBuilder::class);
