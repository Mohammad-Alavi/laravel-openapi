<?php

namespace Tests\oooas\Unit\Schema\Objects;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Encoding;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Example;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Header\Fields\Description;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Header\Header;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\MediaType\MediaType;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\Collections\Content\ContentEntry;

describe(class_basename(Encoding::class), function (): void {
    it('can be created with all parameters', function (): void {
        $header = Header::create('HeaderName')
            ->description(Description::create('Lorem ipsum'))
            ->required()
            ->deprecated()
            ->schema(Schema::string())
            ->example('Example String')
            ->examples(
                Example::create('ExampleName')
                    ->value('Example value'),
            )->content(
                ContentEntry::json(
                    MediaType::create(),
                ),
            );

        $encoding = Encoding::create('EncodingName')
            ->contentType('application/json')
            ->headers($header)
            ->style('simple')
            ->explode()
            ->allowReserved();

        $mediaType = MediaType::create()
            ->encoding($encoding);

        expect($mediaType->asArray())->toBe([
            'encoding' => [
                'EncodingName' => [
                    'contentType' => 'application/json',
                    'headers' => [
                        'HeaderName' => [
                            'description' => 'Lorem ipsum',
                            'required' => true,
                            'deprecated' => true,
                            'schema' => [
                                'type' => 'string',
                            ],
                            'example' => 'Example String',
                            'examples' => [
                                'ExampleName' => [
                                    'value' => 'Example value',
                                ],
                            ],
                            'content' => [
                                'application/json' => [],
                            ],
                        ],
                    ],
                    'style' => 'simple',
                    'explode' => true,
                    'allowReserved' => true,
                ],
            ],
        ]);
    });
})->covers(Encoding::class);
