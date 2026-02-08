<?php

declare(strict_types=1);

use MohammadAlavi\Laragen\ModelSchema\CastAnalyzer;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\JSONSchema;

describe(class_basename(CastAnalyzer::class), function (): void {
    it('maps string cast to string schema', function (): void {
        $schema = CastAnalyzer::resolve('string');

        expect($schema->compile())->toBe(['type' => 'string']);
    });

    it('maps integer casts to integer schema', function (): void {
        foreach (['int', 'integer'] as $cast) {
            $schema = CastAnalyzer::resolve($cast);

            expect($schema->compile()['type'])->toBe('integer');
        }
    });

    it('maps float casts to number schema', function (): void {
        foreach (['float', 'double', 'real'] as $cast) {
            $schema = CastAnalyzer::resolve($cast);

            expect($schema->compile()['type'])->toBe('number');
        }
    });

    it('maps boolean casts to boolean schema', function (): void {
        foreach (['bool', 'boolean'] as $cast) {
            $schema = CastAnalyzer::resolve($cast);

            expect($schema->compile()['type'])->toBe('boolean');
        }
    });

    it('maps collection-like casts to object schema', function (): void {
        foreach (['array', 'collection', 'object'] as $cast) {
            $schema = CastAnalyzer::resolve($cast);

            expect($schema->compile()['type'])->toBe('object');
        }
    });

    it('maps date casts to string with date-time format', function (): void {
        foreach (['date', 'datetime', 'immutable_date', 'immutable_datetime'] as $cast) {
            $schema = CastAnalyzer::resolve($cast);
            $compiled = $schema->compile();

            expect($compiled['type'])->toBe('string')
                ->and($compiled['format'])->toBe('date-time');
        }
    });

    it('maps timestamp cast to integer schema', function (): void {
        $schema = CastAnalyzer::resolve('timestamp');

        expect($schema->compile()['type'])->toBe('integer');
    });

    it('maps decimal cast to string schema', function (): void {
        $schema = CastAnalyzer::resolve('decimal:2');

        expect($schema->compile()['type'])->toBe('string');
    });

    it('maps backed enum to enum schema', function (): void {
        $schema = CastAnalyzer::resolve(Tests\Laragen\Support\Doubles\Models\StatusEnum::class);
        $compiled = $schema->compile();

        expect($compiled)->toHaveKey('enum')
            ->and($compiled['enum'])->toBe(['active', 'inactive', 'pending']);
    });

    it('returns string for unknown cast types', function (): void {
        $schema = CastAnalyzer::resolve('unknown_cast');

        expect($schema->compile()['type'])->toBe('string');
    });

    it('returns JSONSchema interface', function (): void {
        $schema = CastAnalyzer::resolve('string');

        expect($schema)->toBeInstanceOf(JSONSchema::class);
    });
})->covers(CastAnalyzer::class);
