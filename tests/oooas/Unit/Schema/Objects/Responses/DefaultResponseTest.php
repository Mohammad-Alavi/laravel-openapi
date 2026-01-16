<?php

namespace Tests\oooas\Unit\Schema\Objects\Responses;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response\Response;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Fields\DefaultResponse;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Fields\HTTPStatusCode;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Responses;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Support\ResponseEntry;

describe(class_basename(DefaultResponse::class), function (): void {
    it('returns default as value', function (): void {
        $defaultResponse = DefaultResponse::create();

        expect($defaultResponse->value())->toBe('default')
            ->and((string) $defaultResponse)->toBe('default');
    });

    it('serializes to default string', function (): void {
        $defaultResponse = DefaultResponse::create();

        expect($defaultResponse->jsonSerialize())->toBe('default');
    });
})->covers(DefaultResponse::class);

describe('Responses with Default', function (): void {
    it('can include default response in responses', function (): void {
        $responses = Responses::create(
            ResponseEntry::create(
                HTTPStatusCode::ok(),
                Response::create('Successful response'),
            ),
            ResponseEntry::create(
                DefaultResponse::create(),
                Response::create('Default error response'),
            ),
        );

        expect($responses->compile())->toBe([
            '200' => [
                'description' => 'Successful response',
            ],
            'default' => [
                'description' => 'Default error response',
            ],
        ]);
    });

    it('can use only default response', function (): void {
        $responses = Responses::create(
            ResponseEntry::create(
                DefaultResponse::create(),
                Response::create('Catch-all response'),
            ),
        );

        expect($responses->compile())->toBe([
            'default' => [
                'description' => 'Catch-all response',
            ],
        ]);
    });

    it('can mix default with multiple status codes', function (): void {
        $responses = Responses::create(
            ResponseEntry::create(
                HTTPStatusCode::ok(),
                Response::create('Success'),
            ),
            ResponseEntry::create(
                HTTPStatusCode::notFound(),
                Response::create('Not found'),
            ),
            ResponseEntry::create(
                DefaultResponse::create(),
                Response::create('Unexpected error'),
            ),
        );

        $compiled = $responses->compile();

        expect($compiled)->toHaveKeys(['200', '404', 'default'])
            ->and($compiled['200']['description'])->toBe('Success')
            ->and($compiled['404']['description'])->toBe('Not found')
            ->and($compiled['default']['description'])->toBe('Unexpected error');
    });
})->covers(Responses::class);
