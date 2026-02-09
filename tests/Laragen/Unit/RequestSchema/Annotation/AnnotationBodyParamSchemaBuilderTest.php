<?php

declare(strict_types=1);

use Illuminate\Routing\Route;
use MohammadAlavi\Laragen\Annotations\DetectedBodyParam;
use MohammadAlavi\Laragen\RequestSchema\Annotation\AnnotationBodyParamSchemaBuilder;
use MohammadAlavi\Laragen\RequestSchema\ContentEncoding;
use MohammadAlavi\Laragen\RequestSchema\RequestSchemaResult;
use MohammadAlavi\Laragen\RequestSchema\RequestTarget;

describe(class_basename(AnnotationBodyParamSchemaBuilder::class), function (): void {
    it('builds object schema from body param annotations', function (): void {
        $builder = new AnnotationBodyParamSchemaBuilder();
        $route = new Route('POST', '/test', []);
        $params = [
            new DetectedBodyParam('name', 'string', true, "The user's name"),
            new DetectedBodyParam('age', 'integer', false),
            new DetectedBodyParam('is_active', 'boolean', true),
        ];

        $result = $builder->build($params, $route);

        expect($result)->toBeInstanceOf(RequestSchemaResult::class)
            ->and($result->target)->toBe(RequestTarget::BODY)
            ->and($result->encoding)->toBe(ContentEncoding::JSON);

        $compiled = $result->schema->compile();

        expect($compiled)->toHaveKey('type')
            ->and($compiled['type'])->toBe('object')
            ->and($compiled)->toHaveKey('properties')
            ->and($compiled['properties'])->toHaveKey('name')
            ->and($compiled['properties']['name'])->toBe(['type' => 'string'])
            ->and($compiled['properties'])->toHaveKey('age')
            ->and($compiled['properties']['age'])->toBe(['type' => 'integer'])
            ->and($compiled['properties'])->toHaveKey('is_active')
            ->and($compiled['properties']['is_active'])->toBe(['type' => 'boolean'])
            ->and($compiled)->toHaveKey('required')
            ->and($compiled['required'])->toBe(['name', 'is_active']);
    });

    it('handles all supported types', function (): void {
        $builder = new AnnotationBodyParamSchemaBuilder();
        $route = new Route('POST', '/test', []);
        $params = [
            new DetectedBodyParam('title', 'string', false),
            new DetectedBodyParam('count', 'int', false),
            new DetectedBodyParam('score', 'number', false),
            new DetectedBodyParam('active', 'bool', false),
            new DetectedBodyParam('tags', 'array', false),
            new DetectedBodyParam('meta', 'object', false),
        ];

        $result = $builder->build($params, $route);
        $compiled = $result->schema->compile();

        expect($compiled['properties']['title'])->toBe(['type' => 'string'])
            ->and($compiled['properties']['count'])->toBe(['type' => 'integer'])
            ->and($compiled['properties']['score'])->toBe(['type' => 'number'])
            ->and($compiled['properties']['active'])->toBe(['type' => 'boolean'])
            ->and($compiled['properties']['tags'])->toBe(['type' => 'array'])
            ->and($compiled['properties']['meta'])->toBe(['type' => 'object']);
    });

    it('omits required array when no params are required', function (): void {
        $builder = new AnnotationBodyParamSchemaBuilder();
        $route = new Route('POST', '/test', []);
        $params = [
            new DetectedBodyParam('name', 'string', false),
        ];

        $result = $builder->build($params, $route);
        $compiled = $result->schema->compile();

        expect($compiled)->not->toHaveKey('required');
    });

    it('maps integer alias to integer type', function (): void {
        $builder = new AnnotationBodyParamSchemaBuilder();
        $route = new Route('POST', '/test', []);
        $params = [
            new DetectedBodyParam('count', 'integer', false),
        ];

        $result = $builder->build($params, $route);
        $compiled = $result->schema->compile();

        expect($compiled['properties']['count'])->toBe(['type' => 'integer']);
    });
})->covers(AnnotationBodyParamSchemaBuilder::class);
