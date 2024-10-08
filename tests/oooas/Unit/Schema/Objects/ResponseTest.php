<?php

use MohammadAlavi\LaravelOpenApi\oooas\Schema\Objects\Example;
use MohammadAlavi\LaravelOpenApi\oooas\Schema\Objects\Header;
use MohammadAlavi\LaravelOpenApi\oooas\Schema\Objects\Link;
use MohammadAlavi\LaravelOpenApi\oooas\Schema\Objects\MediaType;
use MohammadAlavi\LaravelOpenApi\oooas\Schema\Objects\Response;
use MohammadAlavi\LaravelOpenApi\oooas\Schema\Objects\Schema;

describe('Response', function (): void {
    it('creates a response with all parameters', function (): void {
        $header = Header::create('HeaderName')
            ->description('Lorem ipsum')
            ->required()
            ->deprecated()
            ->allowEmptyValue()
            ->style(Header::STYLE_SIMPLE)
            ->explode()
            ->allowReserved()
            ->schema(Schema::string())
            ->example('Example String')
            ->examples(
                Example::create('ExampleName')
                    ->value('Example value'),
            )
            ->content(MediaType::json());

        $link = Link::create('MyLink');

        $response = Response::create()
            ->statusCode(200)
            ->description('OK')
            ->headers($header)
            ->content(MediaType::json())
            ->links($link);

        expect($response->jsonSerialize())->toBe([
            'description' => 'OK',
            'headers' => [
                'HeaderName' => [
                    'description' => 'Lorem ipsum',
                    'required' => true,
                    'deprecated' => true,
                    'allowEmptyValue' => true,
                    'style' => 'simple',
                    'explode' => true,
                    'allowReserved' => '1',
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

        expect($response->statusCode)->toBe(200);
        expect($response->description)->toBe('OK');
    });

    it('creates a response with created method', function (): void {
        $response = Response::created();

        expect($response->statusCode)->toBe(201);
        expect($response->description)->toBe('Created');
    });

    it('creates a response with moved permanently method', function (): void {
        $response = Response::movedPermanently();

        expect($response->statusCode)->toBe(301);
        expect($response->description)->toBe('Moved Permanently');
    });

    it('creates a response with moved temporarily method', function (): void {
        $response = Response::movedTemporarily();

        expect($response->statusCode)->toBe(302);
        expect($response->description)->toBe('Moved Temporarily');
    });

    it('creates a response with bad request method', function (): void {
        $response = Response::badRequest();

        expect($response->statusCode)->toBe(400);
        expect($response->description)->toBe('Bad Request');
    });

    it('creates a response with unauthorized method', function (): void {
        $response = Response::unauthorized();

        expect($response->statusCode)->toBe(401);
        expect($response->description)->toBe('Unauthorized');
    });

    it('creates a response with forbidden method', function (): void {
        $response = Response::forbidden();

        expect($response->statusCode)->toBe(403);
        expect($response->description)->toBe('Forbidden');
    });

    it('creates a response with not found method', function (): void {
        $response = Response::notFound();

        expect($response->statusCode)->toBe(404);
        expect($response->description)->toBe('Not Found');
    });

    it('creates a response with unprocessable entity method', function (): void {
        $response = Response::unprocessableEntity();

        expect($response->statusCode)->toBe(422);
        expect($response->description)->toBe('Unprocessable Entity');
    });

    it('creates a response with too many requests method', function (): void {
        $response = Response::tooManyRequests();

        expect($response->statusCode)->toBe(429);
        expect($response->description)->toBe('Too Many Requests');
    });

    it('creates a response with internal server error method', function (): void {
        $response = Response::internalServerError();

        expect($response->statusCode)->toBe(500);
        expect($response->description)->toBe('Internal Server Error');
    });
})->covers(Response::class);
