<?php

namespace Tests\oooas\Unit\Schema\Objects;

use MohammadAlavi\LaravelOpenApi\Collections\Path;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Operation;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Fields\Common\Name;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Parameter;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SerializationRule\SchemaSerializedQuery;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem\Fields\Description;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem\Fields\Summary;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem\PathItem;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Paths;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response\Fields\Description as ResponseDescription;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response\Response;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Fields\HTTPStatusCode;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Responses;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Support\ResponseEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Server\Fields\URL;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Server\Server;

describe(class_basename(PathItem::class), function (): void {
    it('can be created with all parameters', function (): void {
        $paths = Paths::create(
            Path::create(
                '/users',
                PathItem::create()
                    ->summary(Summary::create('User endpoints'))
                    ->description(Description::create('Get the users'))
                    ->operations(
                        Operation::get()
                            ->responses(
                                Responses::create(
                                    ResponseEntry::create(
                                        HTTPStatusCode::ok(),
                                        Response::create(ResponseDescription::create('OK')),
                                    ),
                                ),
                            ),
                    )
                    ->servers(Server::create(URL::create('https://example.com')))
                    ->parameters(
                        Parameter::query(
                            Name::create('test_parameter'),
                            SchemaSerializedQuery::create(Schema::string()),
                        ),
                    ),
            ),
        );

        expect($paths->asArray())->toBe([
            '/users' => [
                'summary' => 'User endpoints',
                'description' => 'Get the users',
                'get' => [
                    'responses' => [
                        '200' => [
                            'description' => 'OK',
                        ],
                    ],
                ],
                'servers' => [
                    ['url' => 'https://example.com'],
                ],
                'parameters' => [
                    [
                        'name' => 'test_parameter',
                        'in' => 'query',
                        'schema' => [
                            'type' => 'string',
                        ],
                    ],
                ],
            ],
        ]);
    });
})->covers(PathItem::class);
