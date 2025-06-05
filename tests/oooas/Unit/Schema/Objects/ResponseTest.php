<?php

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Example;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Header\Header;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Link;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\MediaType;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response;

describe('Response', function (): void {
    it('creates a response with all parameters', function (): void {
        $header = Header::create('HeaderName')
            ->description('Lorem ipsum')
            ->required()
            ->deprecated()
            ->schema(Schema::string())
            ->example('Example String')
            ->examples(
                Example::create('ExampleName')
                    ->value('Example value'),
            )
            ->content(MediaType::json());

        $link = Link::create('MyLink');

        $response = Response::ok()
            ->headers($header)
            ->content(MediaType::json())
            ->links($link);

        expect($response->asArray())->toBe([
            'description' => 'OK',
            'headers' => [
                'HeaderName' => [
                    'description' => 'Lorem ipsum',
                    'required' => true,
                    'deprecated' => true,
                    'style' => 'simple',
                    'explode' => true,
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

    it('creates a response with ok method', function (): void {
        $response = Response::ok();

        expect($response->statusCode())->toBe(200)
            ->and($response->description())->toBe('OK');
    });

    it('creates a response with created method', function (): void {
        $response = Response::created();

        expect($response->statusCode())->toBe(201)
            ->and($response->description())->toBe('Created');
    });

    it('creates a response with moved permanently method', function (): void {
        $response = Response::movedPermanently();

        expect($response->statusCode())->toBe(301)
            ->and($response->description())->toBe('Moved Permanently');
    });

    it('creates a response with moved temporarily method', function (): void {
        $response = Response::movedTemporarily();

        expect($response->statusCode())->toBe(302)
            ->and($response->description())->toBe('Moved Temporarily');
    });

    it('creates a response with bad request method', function (): void {
        $response = Response::badRequest();

        expect($response->statusCode())->toBe(400)
            ->and($response->description())->toBe('Bad Request');
    });

    it('creates a response with unauthorized method', function (): void {
        $response = Response::unauthorized();

        expect($response->statusCode())->toBe(401)
            ->and($response->description())->toBe('Unauthorized');
    });

    it('creates a response with forbidden method', function (): void {
        $response = Response::forbidden();

        expect($response->statusCode())->toBe(403)
            ->and($response->description())->toBe('Forbidden');
    });

    it('creates a response with not found method', function (): void {
        $response = Response::notFound();

        expect($response->statusCode())->toBe(404)
            ->and($response->description())->toBe('Not Found');
    });

    it('creates a response with unprocessable entity method', function (): void {
        $response = Response::unprocessableEntity();

        expect($response->statusCode())->toBe(422)
            ->and($response->description())->toBe('Unprocessable Entity');
    });

    it('creates a response with too many requests method', function (): void {
        $response = Response::tooManyRequests();

        expect($response->statusCode())->toBe(429)
            ->and($response->description())->toBe('Too Many Requests');
    });

    it('creates a response with internal server error method', function (): void {
        $response = Response::internalServerError();

        expect($response->statusCode())->toBe(500)
            ->and($response->description())->toBe('Internal Server Error');
    });
})->covers(Response::class);
