<?php

use MohammadAlavi\Laragen\RequestSchema\SchemaToQueryParameters;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Parameter;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;

describe(class_basename(SchemaToQueryParameters::class), function (): void {
    it('converts object schema properties to query parameters', function (): void {
        $schema = Schema::from([
            'type' => 'object',
            'properties' => [
                'page' => ['type' => 'integer'],
                'search' => ['type' => 'string'],
            ],
        ]);

        $converter = new SchemaToQueryParameters();
        $parameters = $converter->convert($schema);

        expect($parameters)->toHaveCount(2)
            ->and($parameters[0])->toBeInstanceOf(Parameter::class)
            ->and($parameters[1])->toBeInstanceOf(Parameter::class);

        $compiled0 = $parameters[0]->compile();
        $compiled1 = $parameters[1]->compile();

        expect($compiled0['name'])->toBe('page')
            ->and($compiled0['in'])->toBe('query')
            ->and($compiled0['schema']['type'])->toBe('integer')
            ->and($compiled1['name'])->toBe('search')
            ->and($compiled1['in'])->toBe('query')
            ->and($compiled1['schema']['type'])->toBe('string');
    });

    it('marks required properties as required parameters', function (): void {
        $schema = Schema::from([
            'type' => 'object',
            'properties' => [
                'page' => ['type' => 'integer'],
                'q' => ['type' => 'string'],
            ],
            'required' => ['q'],
        ]);

        $converter = new SchemaToQueryParameters();
        $parameters = $converter->convert($schema);

        $pageCompiled = $parameters[0]->compile();
        $qCompiled = $parameters[1]->compile();

        expect($pageCompiled)->not->toHaveKey('required')
            ->and($qCompiled['required'])->toBeTrue();
    });

    it('returns empty array when schema has no properties', function (): void {
        $schema = Schema::from([
            'type' => 'object',
        ]);

        $converter = new SchemaToQueryParameters();
        $parameters = $converter->convert($schema);

        expect($parameters)->toBeEmpty();
    });

    it('returns empty array when properties is empty', function (): void {
        $schema = Schema::from([
            'type' => 'object',
            'properties' => [],
        ]);

        $converter = new SchemaToQueryParameters();
        $parameters = $converter->convert($schema);

        expect($parameters)->toBeEmpty();
    });
})->covers(SchemaToQueryParameters::class);
