<?php

declare(strict_types=1);

use MohammadAlavi\Laragen\ResponseSchema\ResponseDetector;
use MohammadAlavi\Laragen\ResponseSchema\ResponseSchemaBuilder;
use MohammadAlavi\Laragen\ResponseSchema\ResponseSchemaResolver;
use MohammadAlavi\Laragen\ResponseSchema\ResponseStrategy;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\JSONSchema;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;

describe(class_basename(ResponseSchemaResolver::class), function (): void {
    it('returns schema from first matching strategy', function (): void {
        $expectedSchema = Schema::string();

        $matchingDetector = Mockery::mock(ResponseDetector::class);
        $matchingDetector->shouldReceive('detect')
            ->with('App\\Http\\Controllers\\FooController', 'index')
            ->andReturn('App\\Http\\Resources\\FooResource');

        $matchingBuilder = Mockery::mock(ResponseSchemaBuilder::class);
        $matchingBuilder->shouldReceive('build')
            ->with('App\\Http\\Resources\\FooResource')
            ->andReturn($expectedSchema);

        $secondDetector = Mockery::mock(ResponseDetector::class);
        $secondDetector->shouldNotReceive('detect');

        $secondBuilder = Mockery::mock(ResponseSchemaBuilder::class);

        $resolver = new ResponseSchemaResolver([
            new ResponseStrategy($matchingDetector, $matchingBuilder),
            new ResponseStrategy($secondDetector, $secondBuilder),
        ]);

        $result = $resolver->resolve('App\\Http\\Controllers\\FooController', 'index');

        expect($result)->toBeInstanceOf(JSONSchema::class);
    });

    it('tries next strategy when first returns null', function (): void {
        $expectedSchema = Schema::object();

        $firstDetector = Mockery::mock(ResponseDetector::class);
        $firstDetector->shouldReceive('detect')->andReturn(null);

        $firstBuilder = Mockery::mock(ResponseSchemaBuilder::class);

        $secondDetector = Mockery::mock(ResponseDetector::class);
        $secondDetector->shouldReceive('detect')
            ->with('App\\Http\\Controllers\\BarController', 'show')
            ->andReturn('App\\Models\\Bar');

        $secondBuilder = Mockery::mock(ResponseSchemaBuilder::class);
        $secondBuilder->shouldReceive('build')
            ->with('App\\Models\\Bar')
            ->andReturn($expectedSchema);

        $resolver = new ResponseSchemaResolver([
            new ResponseStrategy($firstDetector, $firstBuilder),
            new ResponseStrategy($secondDetector, $secondBuilder),
        ]);

        $result = $resolver->resolve('App\\Http\\Controllers\\BarController', 'show');

        expect($result)->toBeInstanceOf(JSONSchema::class);
    });

    it('returns null when no strategy matches', function (): void {
        $firstDetector = Mockery::mock(ResponseDetector::class);
        $firstDetector->shouldReceive('detect')->andReturn(null);

        $firstBuilder = Mockery::mock(ResponseSchemaBuilder::class);

        $resolver = new ResponseSchemaResolver([
            new ResponseStrategy($firstDetector, $firstBuilder),
        ]);

        $result = $resolver->resolve('App\\Http\\Controllers\\BazController', 'index');

        expect($result)->toBeNull();
    });

    it('returns null when no strategies registered', function (): void {
        $resolver = new ResponseSchemaResolver([]);

        $result = $resolver->resolve('App\\Http\\Controllers\\FooController', 'index');

        expect($result)->toBeNull();
    });
})->covers(ResponseSchemaResolver::class);
