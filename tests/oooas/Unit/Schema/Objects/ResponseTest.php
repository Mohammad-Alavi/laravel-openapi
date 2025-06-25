<?php

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Example;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Header\Fields\Description;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Header\Header;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Link\Link;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\MediaType\MediaType;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response\Fields\Description as ResponseDescription;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response\Response;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\SharedFields\Content\ContentEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\SharedFields\Headers\HeaderEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\SharedFields\Links\LinkEntry;

describe('Response', function (): void {
    it('creates a response with all parameters', function (): void {
        $header = Header::create('HeaderName')
            ->description(Description::create('Lorem ipsum'))
            ->required()
            ->deprecated()
            ->schema(Schema::string())
            ->example('Example String')
            ->examples(
                Example::create('ExampleName')
                    ->value('Example value'),
            )
            ->content(
                ContentEntry::json(
                    MediaType::create(),
                ),
            );

        $link = Link::create();

        $response = Response::create(
            ResponseDescription::create('A response indicating success'),
        )->headers(
            HeaderEntry::create(
                'HeaderName',
                $header,
            ),
        )->content(
            ContentEntry::json(
                MediaType::create(),
            ),
        )->links(
            LinkEntry::create('MyLink', $link),
        );

        expect($response->asArray())->toBe([
            'description' => 'A response indicating success',
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
            'content' => [
                'application/json' => [],
            ],
            'links' => [
                'MyLink' => [],
            ],
        ]);
    });
})->covers(Response::class);
