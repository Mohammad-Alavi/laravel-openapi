<?php

declare(strict_types=1);

use Illuminate\Routing\Route;
use MohammadAlavi\Laragen\RequestSchema\ContentEncoding;
use MohammadAlavi\Laragen\RequestSchema\RequestDetector;
use MohammadAlavi\Laragen\RequestSchema\RequestSchemaBuilder;
use MohammadAlavi\Laragen\RequestSchema\RequestSchemaResolver;
use MohammadAlavi\Laragen\RequestSchema\RequestSchemaResult;
use MohammadAlavi\Laragen\RequestSchema\RequestStrategy;
use MohammadAlavi\Laragen\RequestSchema\RequestTarget;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;

describe(class_basename(RequestSchemaResolver::class), function (): void {
    it('returns result from first matching strategy', function (): void {
        $route = new Route('POST', '/test', ['controller' => 'FooController@store']);
        $expectedResult = new RequestSchemaResult(
            Schema::from(['type' => 'object']),
            RequestTarget::BODY,
            ContentEncoding::JSON,
        );

        $matchingDetector = Mockery::mock(RequestDetector::class);
        $matchingDetector->shouldReceive('detect')
            ->with($route, 'FooController', 'store')
            ->andReturn(['title' => 'required']);

        $matchingBuilder = Mockery::mock(RequestSchemaBuilder::class);
        $matchingBuilder->shouldReceive('build')
            ->with(['title' => 'required'], $route)
            ->andReturn($expectedResult);

        $secondDetector = Mockery::mock(RequestDetector::class);
        $secondDetector->shouldNotReceive('detect');

        $secondBuilder = Mockery::mock(RequestSchemaBuilder::class);

        $resolver = new RequestSchemaResolver([
            new RequestStrategy($matchingDetector, $matchingBuilder),
            new RequestStrategy($secondDetector, $secondBuilder),
        ]);

        $result = $resolver->resolve($route, 'FooController', 'store');

        expect($result)->toBeInstanceOf(RequestSchemaResult::class)
            ->and($result->target)->toBe(RequestTarget::BODY);
    });

    it('tries next strategy when first returns null', function (): void {
        $route = new Route('GET', '/test', ['controller' => 'BarController@index']);
        $expectedResult = new RequestSchemaResult(
            Schema::from(['type' => 'object']),
            RequestTarget::QUERY,
            ContentEncoding::JSON,
        );

        $firstDetector = Mockery::mock(RequestDetector::class);
        $firstDetector->shouldReceive('detect')->andReturn(null);

        $firstBuilder = Mockery::mock(RequestSchemaBuilder::class);

        $secondDetector = Mockery::mock(RequestDetector::class);
        $secondDetector->shouldReceive('detect')
            ->with($route, 'BarController', 'index')
            ->andReturn(['search' => 'required']);

        $secondBuilder = Mockery::mock(RequestSchemaBuilder::class);
        $secondBuilder->shouldReceive('build')
            ->with(['search' => 'required'], $route)
            ->andReturn($expectedResult);

        $resolver = new RequestSchemaResolver([
            new RequestStrategy($firstDetector, $firstBuilder),
            new RequestStrategy($secondDetector, $secondBuilder),
        ]);

        $result = $resolver->resolve($route, 'BarController', 'index');

        expect($result)->toBeInstanceOf(RequestSchemaResult::class)
            ->and($result->target)->toBe(RequestTarget::QUERY);
    });

    it('returns null when no strategy matches', function (): void {
        $route = new Route('GET', '/test', ['controller' => 'BazController@index']);

        $detector = Mockery::mock(RequestDetector::class);
        $detector->shouldReceive('detect')->andReturn(null);

        $builder = Mockery::mock(RequestSchemaBuilder::class);

        $resolver = new RequestSchemaResolver([
            new RequestStrategy($detector, $builder),
        ]);

        $result = $resolver->resolve($route, 'BazController', 'index');

        expect($result)->toBeNull();
    });

    it('returns null when no strategies registered', function (): void {
        $route = new Route('GET', '/test', ['controller' => 'FooController@index']);

        $resolver = new RequestSchemaResolver([]);

        $result = $resolver->resolve($route, 'FooController', 'index');

        expect($result)->toBeNull();
    });
})->covers(RequestSchemaResolver::class);
