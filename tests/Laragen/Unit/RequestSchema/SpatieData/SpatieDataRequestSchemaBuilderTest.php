<?php

declare(strict_types=1);

use Illuminate\Routing\Route;
use MohammadAlavi\Laragen\RequestSchema\ContentEncoding;
use MohammadAlavi\Laragen\RequestSchema\RequestSchemaResult;
use MohammadAlavi\Laragen\RequestSchema\RequestTarget;
use MohammadAlavi\Laragen\RequestSchema\SpatieData\SpatieDataRequestSchemaBuilder;
use MohammadAlavi\Laragen\ResponseSchema\SpatieData\SpatieDataSchemaBuilder;
use Tests\Laragen\Support\Doubles\DataObjects\UserData;

describe(class_basename(SpatieDataRequestSchemaBuilder::class), function (): void {
    it('builds request schema from Spatie Data class', function (): void {
        $responseBuilder = new SpatieDataSchemaBuilder();
        $builder = new SpatieDataRequestSchemaBuilder($responseBuilder);

        $route = new Route('POST', '/test', []);
        $result = $builder->build(UserData::class, $route);

        expect($result)->toBeInstanceOf(RequestSchemaResult::class)
            ->and($result->target)->toBe(RequestTarget::BODY)
            ->and($result->encoding)->toBe(ContentEncoding::JSON);

        $compiled = $result->schema->compile();

        expect($compiled)->toHaveKey('properties')
            ->and($compiled['properties'])->toHaveKey('name')
            ->and($compiled['properties'])->toHaveKey('age')
            ->and($compiled['properties'])->toHaveKey('is_active');
    });
})->covers(SpatieDataRequestSchemaBuilder::class);
