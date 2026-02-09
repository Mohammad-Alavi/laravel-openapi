<?php

use MohammadAlavi\Laragen\RequestSchema\ExampleGenerator\ExampleGenerator;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Properties\Property;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Type;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\StrictFluentDescriptor;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;
use Pest\Expectation;

describe(class_basename(ExampleGenerator::class), function (): void {
    it('generates examples for string properties', function (): void {
        $descriptor = Schema::string()
            ->maxLength(255)
            ->minLength(3);

        $descriptor = (new ExampleGenerator())->for($descriptor);

        expect($descriptor->getExamples())->toHaveCount(1)
            ->and($descriptor->getExamples()[0])->toBeString()
            ->not->toBeEmpty();
    });

    it('generates examples for integer properties', function (): void {
        $descriptor = Schema::integer()
            ->maximum(100)
            ->minimum(1);

        $descriptor = (new ExampleGenerator())->for($descriptor);

        expect($descriptor->getExamples())->toHaveCount(1)
            ->and($descriptor->getExamples()[0])->toBeInt()
            ->toBeGreaterThanOrEqual(1)
            ->toBeLessThanOrEqual(100);
    });

    it('generates examples for number properties', function (): void {
        $descriptor = Schema::number()
            ->maximum(100.0)
            ->minimum(1.0);

        $descriptor = (new ExampleGenerator())->for($descriptor);

        expect($descriptor->getExamples())->toHaveCount(1)
            ->and($descriptor->getExamples()[0])->toBeFloat()
            ->toBeGreaterThanOrEqual(1.0)
            ->toBeLessThanOrEqual(100.0);
    });

    it('generates examples for boolean properties', function (): void {
        $descriptor = Schema::boolean();

        $descriptor = (new ExampleGenerator())->for($descriptor);

        expect($descriptor->getExamples())->toHaveCount(1)
            ->and($descriptor->getExamples()[0])->toBeBool();
    });

    it('generates examples for enum properties', function (): void {
        $descriptor = Schema::enum('red', 'green', 'blue');

        $descriptor = (new ExampleGenerator())->for($descriptor);

        expect($descriptor->getExamples())->toHaveCount(1)
            ->and($descriptor->getExamples()[0])->toBeIn(['red', 'green', 'blue']);
    });

    it('generates examples for object properties', function (): void {
        $descriptor = Schema::object()
            ->properties(
                Property::create(
                    'name',
                    Schema::string()->maxLength(50),
                ),
                Property::create(
                    'age',
                    Schema::integer()->minimum(18)->maximum(99),
                ),
            );

        $descriptor = (new ExampleGenerator())->for($descriptor);

        [$nameProperty, $ageProperty] = $descriptor->getProperties();
        expect($descriptor->getProperties())->toHaveCount(2)
            ->and($nameProperty->name())->toBe('name')
            ->and($nameProperty->schema()->getExamples())->toHaveCount(1)
            ->and($nameProperty->schema()->getExamples()[0])->toBeString()
            ->not->toBeEmpty()
            ->and($ageProperty->name())->toBe('age')
            ->and($ageProperty->schema()->getExamples())->toHaveCount(1)
            ->and($ageProperty->schema()->getExamples()[0])->toBeInt()
            ->toBeGreaterThanOrEqual(18)
            ->toBeLessThanOrEqual(99);
    });

    it('generates examples for array properties', function (): void {
        $descriptor = Schema::array()
            ->items(Schema::string()->maxLength(50));

        $descriptor = (new ExampleGenerator())->for($descriptor);

        expect($descriptor->getExamples())->toHaveCount(3)
            ->each(
                function (Expectation $example) {
                    $example->toBeString()
                        ->not->toBeEmpty();
                },
            );
    });

    it('generates examples for multi-type properties', function (): void {
        $descriptor = StrictFluentDescriptor::withoutSchema()
            ->type(Type::string(), Type::null(), Type::boolean(), Type::integer())
            ->oneOf(
                Schema::string()->maxLength(50),
                Schema::null(),
                Schema::integer()->minimum(1)->maximum(100),
            );

        $descriptor = (new ExampleGenerator())->for($descriptor);

        expect($descriptor->getExamples())->toHaveCount(4)
            ->and($descriptor->getExamples()[0])->toBeString()
            ->not->toBeEmpty()
            ->and($descriptor->getExamples()[1])->toBeNull()
            ->and($descriptor->getExamples()[2])->toBeBool()
            ->and($descriptor->getExamples()[3])->toBeInt()
            ->toBeGreaterThanOrEqual(1)
            ->toBeLessThanOrEqual(100);
    });

    it('doesnt overwrite existing examples', function (): void {
        $descriptor = Schema::string()
            ->maxLength(255)
            ->minLength(3)
            ->examples('example');

        $descriptor = (new ExampleGenerator())->for($descriptor);

        expect($descriptor->getExamples())->toHaveCount(1)
            ->and($descriptor->getExamples()[0])->toBe('example');
    });

    it('adds and merges missing examples with existing examples', function (): void {
        $descriptor = Schema::object()
            ->properties(
                Property::create(
                    'name',
                    Schema::string()->maxLength(50),
                ),
                Property::create(
                    'age',
                    Schema::integer()->minimum(18)->maximum(99),
                ),
            )
            ->examples(
                [
                    'name' => 'John Doe',
                ],
            );

        $descriptor = (new ExampleGenerator())->for($descriptor);

        expect($descriptor->getExamples())->toHaveCount(1)
            ->and($descriptor->getExamples()[0])->toBeArray()
            ->and($descriptor->getExamples()[0]['name'])->not->toBeEmpty()
            ->and($descriptor->getExamples()[0]['age'])->toBeInt()
            ->toBeGreaterThanOrEqual(18)
            ->toBeLessThanOrEqual(99);
    });
})->covers(ExampleGenerator::class);
