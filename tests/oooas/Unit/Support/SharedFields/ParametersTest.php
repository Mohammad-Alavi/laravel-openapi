<?php

namespace Tests\oooas\Unit\Support\SharedFields;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\ParameterFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Parameter;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SerializationRule\PathParameter;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SerializationRule\QueryParameter;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Parameters;

describe(class_basename(Parameters::class), function (): void {
    it('can create with Parameter instances', function (): void {
        $param1 = Parameter::path('id', PathParameter::create(Schema::string()));
        $param2 = Parameter::query('page', QueryParameter::create(Schema::integer()));

        $parameters = Parameters::create($param1, $param2);

        expect($parameters->toArray())->toHaveCount(2)
            ->and($parameters->toArray()[0])->toBe($param1)
            ->and($parameters->toArray()[1])->toBe($param2);
    });

    it('can create with ParameterFactory instances', function (): void {
        $factory1 = new class extends ParameterFactory {
            public function component(): Parameter
            {
                return Parameter::path('id', PathParameter::create(Schema::string()));
            }
        };

        $factory2 = new class extends ParameterFactory {
            public function component(): Parameter
            {
                return Parameter::query('page', QueryParameter::create(Schema::integer()));
            }
        };

        $parameters = Parameters::create($factory1, $factory2);

        expect($parameters->toArray())->toHaveCount(2);
    });

    it('removes duplicate Parameter instances with same name and location', function (): void {
        $param1 = Parameter::path('id', PathParameter::create(Schema::string()));
        $param2 = Parameter::path('id', PathParameter::create(Schema::integer())); // Same name+location

        $parameters = Parameters::create($param1, $param2);

        expect($parameters->toArray())->toHaveCount(1);
    });

    it('keeps last occurrence when duplicates exist', function (): void {
        $param1 = Parameter::path('id', PathParameter::create(Schema::string()));
        $param2 = Parameter::path('id', PathParameter::create(Schema::integer())); // Same name+location, different schema

        $parameters = Parameters::create($param1, $param2);

        // Should keep the last one (param2 with integer schema)
        $result = $parameters->toArray()[0];
        expect($result->compile()['schema']['type'])->toBe('integer');
    });

    it('does not consider parameters with different locations as duplicates', function (): void {
        $pathParam = Parameter::path('id', PathParameter::create(Schema::string()));
        $queryParam = Parameter::query('id', QueryParameter::create(Schema::string())); // Same name, different location

        $parameters = Parameters::create($pathParam, $queryParam);

        expect($parameters->toArray())->toHaveCount(2);
    });

    it('can merge Parameters instances', function (): void {
        $param1 = Parameter::path('id', PathParameter::create(Schema::string()));
        $param2 = Parameter::query('page', QueryParameter::create(Schema::integer()));

        $parameters1 = Parameters::create($param1);
        $parameters2 = Parameters::create($param2);

        $merged = Parameters::create($parameters1, $parameters2);

        expect($merged->toArray())->toHaveCount(2);
    });

    it('removes duplicates when merging Parameters instances', function (): void {
        $param1 = Parameter::path('id', PathParameter::create(Schema::string()));
        $param2 = Parameter::path('id', PathParameter::create(Schema::integer())); // Duplicate

        $parameters1 = Parameters::create($param1);
        $parameters2 = Parameters::create($param2);

        $merged = Parameters::create($parameters1, $parameters2);

        expect($merged->toArray())->toHaveCount(1);
    });

    it('keeps last occurrence when merging Parameters with duplicates', function (): void {
        $param1 = Parameter::path('id', PathParameter::create(Schema::string()));
        $param2 = Parameter::path('id', PathParameter::create(Schema::integer()));

        $parameters1 = Parameters::create($param1);
        $parameters2 = Parameters::create($param2);

        // parameters2 comes last, so its param should be kept
        $merged = Parameters::create($parameters1, $parameters2);

        $result = $merged->toArray()[0];
        expect($result->compile()['schema']['type'])->toBe('integer');
    });
})->covers(Parameters::class);
