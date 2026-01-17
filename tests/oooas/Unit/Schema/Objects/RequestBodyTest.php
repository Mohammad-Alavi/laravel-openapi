<?php

namespace Tests\oooas\Unit\Schema\Objects;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\MediaType\MediaType;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\RequestBody\RequestBody;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Content\ContentEntry;
use Webmozart\Assert\InvalidArgumentException;

describe(class_basename(RequestBody::class), function (): void {
    it('can be created with content', function (): void {
        $requestBody = RequestBody::create(
            ContentEntry::json(MediaType::create()),
        );

        expect($requestBody->compile())->toBe([
            'content' => [
                'application/json' => [],
            ],
        ]);
    });

    it('can be created with all parameters', function (): void {
        $requestBody = RequestBody::create(
            ContentEntry::json(MediaType::create()),
        )
            ->description('Standard request')
            ->content(
                ContentEntry::xml(MediaType::create()),
            )
            ->required();

        expect($requestBody->compile())->toBe([
            'description' => 'Standard request',
            'content' => [
                'text/xml' => [],
            ],
            'required' => true,
        ]);
    });

    it('throws exception when created without content', function (): void {
        expect(fn () => RequestBody::create())
            ->toThrow(InvalidArgumentException::class, 'RequestBody content is required per OpenAPI spec.');
    });

    it('can add additional content entries', function (): void {
        $requestBody = RequestBody::create(
            ContentEntry::json(MediaType::create()),
        )->content(
            ContentEntry::json(MediaType::create()),
            ContentEntry::xml(MediaType::create()),
        );

        expect($requestBody->compile())->toBe([
            'content' => [
                'application/json' => [],
                'text/xml' => [],
            ],
        ]);
    });
})->covers(RequestBody::class);
