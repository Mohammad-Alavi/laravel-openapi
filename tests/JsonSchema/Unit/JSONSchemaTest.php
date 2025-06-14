<?php

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\DependentRequired\Dependency;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Properties\Property;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\LooseFluentDescriptor;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Formats\IntegerFormat;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;

describe(class_basename(LooseFluentDescriptor::class), function (): void {
    it(
        'can be used to create a json file of an specific JSONSchema dialect',
        function (): void {
            jsonDD(
                Schema::integer()->format(IntegerFormat::INT64)->comment('Format test'),
            );
            jsonDD(
                Schema::enum('sag', 'ga')->comment('This is a enum'),
            );
            jsonDD(
                Schema::const('sag')->comment('This is a const'),
            );
            jsonDD(
                Schema::object()
                        ->comment('This is an object')
                    ->properties(
                        Property::create('name', Schema::string()->comment('This is a name')),
                    )
                    ->writeOnly()
                    ->oneOf(
                        Schema::string(),
                        Schema::number(),
                    )->dependentRequired(
                        Dependency::create('name', 'name'),
                        Dependency::create('sag', 'dick', 'wat'),
                    )->required('name'),
            );
            jsonDD(
                Schema::array()
                        ->comment('This is a null')
                        ->maxContains(3)
                        ->uniqueItems()
                        ->items(
                            Schema::string(),
                        )->oneOf(
                            Schema::string(),
                            Schema::number(),
                        ),
            );
            //            jsonDD(Schema::string()->comment('This is a string'));
            //            jsonDD(Schema::number()->double()->comment('This is a comment'));
            //            jsonDD(Schema::oas31()->integer()->int64());
            //            jsonDD(Schema::oas31()->string()->format(StringFormat::DATE));
            //            jsonDD(Schema::oas31()->integer()->int32()->);
            //            jsonDD(Schema::string());
            //            jsonDD(Schema::string()->id('sag'));

            //            $stringSchema = $schema->metaSchema()->availableKeywords()
            //                ->string();
            // ->minLength(3)
            // ->maxLength(10);

            // expect($stringSchema->asArray())->toBe('{"type":"string","minLength":3,"maxLength":10}');
            expect(\Safe\json_encode(LooseFluentDescriptor::create()->type('string')))->toBe('{"type":"string"}');
        },
    );

    function jsonDD(mixed $value): void
    {
        dd(
            \Safe\json_encode(
                $value,
                JSON_PRETTY_PRINT,
            ),
        );
    }
})->covers(LooseFluentDescriptor::class)->skip();
