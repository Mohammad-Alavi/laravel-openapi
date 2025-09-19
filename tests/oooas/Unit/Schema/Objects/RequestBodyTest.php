<?php

namespace Tests\oooas\Unit\Schema\Objects;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\MediaType\MediaType;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\RequestBody\RequestBody;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Content\ContentEntry;

describe(class_basename(RequestBody::class), function (): void {
    it('can be created with all parameters', function (): void {
        $requestBody = RequestBody::create()
            ->description('Standard request')
            ->content(
                ContentEntry::json(
                    MediaType::create(),
                ),
            )->required();

        expect($requestBody->compile())->toBe([
            'description' => 'Standard request',
            'content' => [
                'application/json' => [],
            ],
            'required' => true,
        ]);
    });
})->covers(RequestBody::class);
