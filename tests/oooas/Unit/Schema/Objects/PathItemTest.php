<?php

namespace Tests\oooas\Unit\Schema\Objects;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Operation\Operation;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Parameter;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SerializationRule\QueryParameter;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem\PathItem;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem\Support\AvailableOperation;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem\Support\HttpMethod;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Paths\Fields\Path;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Paths\Paths;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response\Response;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Fields\HTTPStatusCode;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Responses;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Support\ResponseEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Server\Server;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Parameters;

describe(class_basename(PathItem::class), function (): void {
    it('can be created with all parameters', function (): void {
        $paths = Paths::create(
            Path::create(
                '/users',
                PathItem::create()
                    ->summary('User endpoints')
                    ->description('Get the users')
                    ->operations(
                        AvailableOperation::create(
                            HttpMethod::GET,
                            Operation::create()
                                ->operationId('getUsers')
                                ->responses(
                                    Responses::create(
                                        ResponseEntry::create(
                                            HTTPStatusCode::ok(),
                                            Response::create('OK'),
                                        ),
                                    ),
                                ),
                        ),
                    )
                    ->servers(Server::create('https://laragen.io'))
                    ->parameters(
                        Parameters::create(
                            Parameter::query(
                                'test_parameter',
                                QueryParameter::create(Schema::string()),
                            ),
                        ),
                    ),
            ),
        );

        expect($paths->unserializeToArray())->toBe([
            '/users' => [
                'summary' => 'User endpoints',
                'description' => 'Get the users',
                'get' => [
                    'operationId' => 'getUsers',
                    'responses' => [
                        '200' => [
                            'description' => 'OK',
                        ],
                    ],
                ],
                'servers' => [
                    ['url' => 'https://laragen.io'],
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
