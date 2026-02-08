<?php

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Example\Example;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Header\Header;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Link\Link;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\MediaType\MediaType;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response\Response;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Serialization\Content;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Serialization\HeaderParameter;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Content\ContentEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Examples\ExampleEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Headers\HeaderEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Links\LinkEntry;

describe('Response', function (): void {
    it('creates a response with all parameters', function (): void {
        $header = Header::create(
            HeaderParameter::create(Schema::string()),
        )->description('Lorem ipsum')
            ->required()
            ->deprecated()
            ->examples(
                ExampleEntry::create(
                    'ExampleName',
                    Example::create()
                        ->value('Example value'),
                ),
            );

        $link = Link::create();

        $response = Response::create()
            ->description('A response indicating success')
            ->headers(
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
        $header = Header::create(
            Content::create(
                ContentEntry::json(
                    MediaType::create(),
                ),
            ),
        )->description('Lorem ipsum')
            ->required();

        $response = Response::create()
            ->description('A response indicating success')
            ->headers(
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
    it('can set summary field', function (): void {
        $response = Response::create()
            ->description('OK')
            ->summary('Successful operation');

        expect($response->compile())->toBe([
            'description' => 'OK',
            'summary' => 'Successful operation',
        ]);
    });

    it('can set summary with other fields', function (): void {
        $response = Response::create()
            ->description('OK')
            ->summary('Successful operation')
            ->content(
                ContentEntry::json(
                    MediaType::create(),
                ),
            );

        expect($response->compile())->toBe([
            'description' => 'OK',
            'summary' => 'Successful operation',
            'content' => [
                'application/json' => [],
            ],
        ]);
    });
})->covers(Response::class);
