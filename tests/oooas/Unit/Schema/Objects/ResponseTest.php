<?php

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Example\Example;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Header\Header;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Link\Link;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\MediaType\MediaType;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response\Response;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Content\ContentEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Examples\ExampleEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Headers\HeaderEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Links\LinkEntry;

describe('Response', function (): void {
    it('creates a response with all parameters', function (): void {
        $header = Header::create()
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
            );

        $link = Link::create();

        $response = Response::create(
            'A response indicating success',
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

        expect($response->compile())->toBe([
            'description' => 'A response indicating success',
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

    it('creates a response with content-based header', function (): void {
        $header = Header::create()
            ->description('Lorem ipsum')
            ->required()
            ->content(
                ContentEntry::json(
                    MediaType::create(),
                ),
            );

        $response = Response::create(
            'A response indicating success',
        )->headers(
            HeaderEntry::create(
                'HeaderName',
                $header,
            ),
        );

        expect($response->compile())->toBe([
            'description' => 'A response indicating success',
            'headers' => [
                'HeaderName' => [
                    'description' => 'Lorem ipsum',
                    'required' => true,
                    'content' => [
                        'application/json' => [],
                    ],
                ],
            ],
        ]);
    });
})->covers(Response::class);
