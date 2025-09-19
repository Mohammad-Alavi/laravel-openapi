<?php

namespace Tests\oooas\Unit\Schema\Objects;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Encoding\Encoding;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Example\Example;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Header\Header;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\MediaType\Fields\Encoding\EncodingEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\MediaType\MediaType;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Content\ContentEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Examples\ExampleEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Headers\HeaderEntry;

describe(class_basename(Encoding::class), function (): void {
    it('can be created with all parameters', function (): void {
        $header = HeaderEntry::create(
            'HeaderName',
            Header::create()
                ->description('Lorem ipsum')
                ->required()
                ->deprecated()
                ->schema(Schema::string())
                ->examples(
                    ExampleEntry::create(
                        'ExampleName',
                        Example::create()
                            ->value('Example value'),
                    ),
                )->content(
                    ContentEntry::json(
                        MediaType::create(),
                    ),
                ),
        );

        $encoding = EncodingEntry::create(
            'EncodingName',
            Encoding::create()
                ->contentType('application/json')
                ->headers($header)
                ->style('simple')
                ->explode()
                ->allowReserved(),
        );

        $mediaType = MediaType::create()
            ->encoding($encoding);

        expect($mediaType->compile())->toBe([
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
