<?php

use MohammadAlavi\LaravelOpenApi\oooas\Schema\Objects\Encoding;
use MohammadAlavi\LaravelOpenApi\oooas\Schema\Objects\Example;
use MohammadAlavi\LaravelOpenApi\oooas\Schema\Objects\MediaType;
use MohammadAlavi\LaravelOpenApi\oooas\Schema\Objects\Schema;

describe('MediaType', function (): void {
    it('can be created with no parameters', function (): void {
        $mediaType = MediaType::create();

        expect($mediaType->jsonSerialize())->toBeEmpty();
    });

    it('can be created with all parameters', function (): void {
        $mediaType = MediaType::create()
            ->mediaType(MediaType::MEDIA_TYPE_APPLICATION_JSON)
            ->schema(Schema::object())
            ->examples(Example::create('ExampleName'), Example::create('ExampleName2'))
            ->example(Example::create())
            ->encoding(Encoding::create('EncodingName'));

        expect($mediaType->jsonSerialize())->toBe([
            'schema' => [
                'type' => 'object',
            ],
            'example' => [],
            'examples' => [
                'ExampleName' => [],
                'ExampleName2' => [],
            ],
            'encoding' => [
                'EncodingName' => [],
            ],
        ]);
    });

    it('can be created with predefined media types', function (string $method, string $expectedType): void {
        $mediaType = MediaType::$method();

        expect($mediaType->mediaType)->toBe($expectedType);
    })->with([
        'json' => ['json', MediaType::MEDIA_TYPE_APPLICATION_JSON],
        'pdf' => ['pdf', MediaType::MEDIA_TYPE_APPLICATION_PDF],
        'jpeg' => ['jpeg', MediaType::MEDIA_TYPE_IMAGE_JPEG],
        'png' => ['png', MediaType::MEDIA_TYPE_IMAGE_PNG],
        'calendar' => ['calendar', MediaType::MEDIA_TYPE_TEXT_CALENDAR],
        'plainText' => ['plainText', MediaType::MEDIA_TYPE_TEXT_PLAIN],
        'xml' => ['xml', MediaType::MEDIA_TYPE_TEXT_XML],
        'formUrlEncoded' => ['formUrlEncoded', MediaType::MEDIA_TYPE_APPLICATION_X_WWW_FORM_URLENCODED],
    ])->with([
        'objectId' => ['objectId'],
    ]);
})->covers(MediaType::class);
