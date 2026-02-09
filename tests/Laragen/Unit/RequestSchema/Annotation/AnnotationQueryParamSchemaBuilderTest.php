<?php

declare(strict_types=1);

use Illuminate\Routing\Route;
use MohammadAlavi\Laragen\Annotations\DetectedQueryParam;
use MohammadAlavi\Laragen\RequestSchema\Annotation\AnnotationQueryParamSchemaBuilder;
use MohammadAlavi\Laragen\RequestSchema\ContentEncoding;
use MohammadAlavi\Laragen\RequestSchema\RequestSchemaResult;
use MohammadAlavi\Laragen\RequestSchema\RequestTarget;

describe(class_basename(AnnotationQueryParamSchemaBuilder::class), function (): void {
    it('builds object schema from query param annotations', function (): void {
        $builder = new AnnotationQueryParamSchemaBuilder();
        $route = new Route('GET', '/test', []);
        $params = [
            new DetectedQueryParam('page', 'integer', 'The page number'),
            new DetectedQueryParam('per_page', 'integer'),
            new DetectedQueryParam('search', 'string', 'The search term'),
        ];

        $result = $builder->build($params, $route);

        expect($result)->toBeInstanceOf(RequestSchemaResult::class)
            ->and($result->target)->toBe(RequestTarget::QUERY)
            ->and($result->encoding)->toBe(ContentEncoding::JSON);

        $compiled = $result->schema->compile();

        expect($compiled)->toHaveKey('type')
            ->and($compiled['type'])->toBe('object')
            ->and($compiled)->toHaveKey('properties')
            ->and($compiled['properties'])->toHaveKey('page')
            ->and($compiled['properties']['page'])->toBe(['type' => 'integer'])
            ->and($compiled['properties'])->toHaveKey('per_page')
            ->and($compiled['properties']['per_page'])->toBe(['type' => 'integer'])
            ->and($compiled['properties'])->toHaveKey('search')
            ->and($compiled['properties']['search'])->toBe(['type' => 'string']);
    });

    it('handles all supported types', function (): void {
        $builder = new AnnotationQueryParamSchemaBuilder();
        $route = new Route('GET', '/test', []);
        $params = [
            new DetectedQueryParam('filter', 'string'),
            new DetectedQueryParam('limit', 'int'),
            new DetectedQueryParam('active', 'bool'),
        ];

        $result = $builder->build($params, $route);
        $compiled = $result->schema->compile();

        expect($compiled['properties']['filter'])->toBe(['type' => 'string'])
            ->and($compiled['properties']['limit'])->toBe(['type' => 'integer'])
            ->and($compiled['properties']['active'])->toBe(['type' => 'boolean']);
    });

    it('defaults unknown types to string', function (): void {
        $builder = new AnnotationQueryParamSchemaBuilder();
        $route = new Route('GET', '/test', []);
        $params = [
            new DetectedQueryParam('custom', 'unknown_type'),
        ];

        $result = $builder->build($params, $route);
        $compiled = $result->schema->compile();

        expect($compiled['properties']['custom'])->toBe(['type' => 'string']);
    });
})->covers(AnnotationQueryParamSchemaBuilder::class);
