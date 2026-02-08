<?php

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Callback\Callback;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\MediaType\MediaType;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Operation\Operation;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem\PathItem;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem\Support\AvailableOperation;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem\Support\HttpMethod;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\RequestBody\RequestBody;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response\Response;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Fields\HTTPStatusCode;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Responses;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Support\ResponseEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\RuntimeExpression\Request\RequestQueryExpression;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Content\ContentEntry;

describe(class_basename(Callback::class), function (): void {
    it(
        'can be created',
        function (HttpMethod $method): void {
            $callback = Callback::create(
                RequestQueryExpression::create('callbackUrl'),
                PathItem::create()
                    ->operations(
                        AvailableOperation::create(
                            $method,
                            Operation::create()
                                ->operationId('myEvent')
                                ->requestBody(
                                    RequestBody::create(ContentEntry::json(MediaType::create()))
                                        ->description(
                                            'something happened',
                                        ),
                                )->responses(
                                    Responses::create(
                                        ResponseEntry::create(
                                            HTTPStatusCode::unauthorized(),
                                            Response::create()->description('Unauthorized'),
                                        ),
                                    ),
                                ),
                        ),
                    ),
                'MyEvent',
            );

            expect($callback)->compile()->toHaveKey(
                '{$request.query.callbackUrl}',
                [
                    $method->value => [
                        'requestBody' => [
                            'description' => 'something happened',
                            'content' => [
                                'application/json' => [],
                            ],
                        ],
                        'responses' => [
                            401 => [
                                'description' => 'Unauthorized',
                            ],
                        ],
                        'operationId' => 'myEvent',
                    ],
                ],
            )->name()->toBe('MyEvent');
        },
    )->with([
        'get action' => [HttpMethod::GET],
        'put action' => [HttpMethod::PUT],
        'post action' => [HttpMethod::POST],
        'delete action' => [HttpMethod::DELETE],
        'options action' => [HttpMethod::OPTIONS],
        'head action' => [HttpMethod::HEAD],
        'patch action' => [HttpMethod::PATCH],
        'trace action' => [HttpMethod::TRACE],
    ]);
})->covers(Callback::class);
