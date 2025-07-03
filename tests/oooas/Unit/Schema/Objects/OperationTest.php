<?php

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\ExternalDocumentation\ExternalDocumentation;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Operation\Operation;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Parameter;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SerializationRule\QueryParameter;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\RequestBody\RequestBody;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response\Response;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Fields\HTTPStatusCode;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Responses;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Support\ResponseEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Server\Server;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Tag\Tag;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Parameters;
use Tests\src\Support\Doubles\Stubs\Attributes\TestCallbackFactory;
use Tests\src\Support\Doubles\Stubs\Petstore\Security\SecuritySchemes\TestBearerSecuritySchemeFactory;
use Tests\src\Support\Doubles\Stubs\Petstore\Security\TestSingleHTTPBearerSchemeSecurityFactory;

describe(class_basename(Operation::class), function (): void {
    it('can be created with no parameters', function (): void {
        $operation = Operation::create();

        expect($operation->unserializeToArray())->toBeEmpty();
    });

    it(
        'can can be created with all parameters',
        function (): void {
            $operation = Operation::create()
                ->tags(
                    Tag::create('Users')->description('Lorem ipsum'),
                    Tag::create('Admins'),
                )->summary('Lorem ipsum')
                ->description('Dolar sit amet')
                ->externalDocs(ExternalDocumentation::create('https://laragen.io/docs'))
                ->operationId('users.show')
                ->parameters(
                    Parameters::create(
                        Parameter::query(
                            'id',
                            QueryParameter::create(Schema::string()),
                        ),
                    ),
                )->requestBody(RequestBody::create())
                ->responses(
                    Responses::create(
                        ResponseEntry::create(
                            HTTPStatusCode::ok(),
                            Response::create('OK'),
                        ),
                    ),
                )->deprecated()
                ->security(app(TestSingleHTTPBearerSchemeSecurityFactory::class)->build())
                ->servers(Server::default())
                ->callbacks(TestCallbackFactory::create());

            expect($operation->unserializeToArray())->toBe([
                'tags' => ['Users', 'Admins'],
                'summary' => 'Lorem ipsum',
                'description' => 'Dolar sit amet',
                'externalDocs' => [
                    'url' => 'https://laragen.io/docs',
                ],
                'operationId' => 'users.show',
                'parameters' => [
                    [
                        'name' => 'id',
                        'in' => 'query',
                        'schema' => [
                            'type' => 'string',
                        ],
                    ],
                ],
                'requestBody' => [],
                'responses' => [
                    '200' => [
                        'description' => 'OK',
                    ],
                ],
                'deprecated' => true,
                'security' => [
                    [
                        TestBearerSecuritySchemeFactory::name() => [],
                    ],
                ],
                'servers' => [
                    [
                        'url' => '/',
                    ],
                ],
                'callbacks' => [
                    'TestCallbackFactory' => [
                        'https://laragen.io/' => [],
                    ],
                ],
            ]);
        },
    );
})->covers(Operation::class);
