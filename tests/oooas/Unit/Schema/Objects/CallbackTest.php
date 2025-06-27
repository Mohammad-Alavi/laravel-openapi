<?php

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Callback\Callback;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Operation\Operation;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem\PathItem;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem\Support\AvailableOperation;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem\Support\HttpMethod;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\RequestBody\Fields\Description;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\RequestBody\RequestBody;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response\Fields\Description as ResponseDescription;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response\Response;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Fields\HTTPStatusCode;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Responses;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Support\ResponseEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\RuntimeExpression\Request\RequestQueryExpression;

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
                                ->requestBody(
                                    RequestBody::create()
                                        ->description(
                                            Description::create('something happened'),
                                        ),
                                )->responses(
                                    Responses::create(
                                        ResponseEntry::create(
                                            HTTPStatusCode::unauthorized(),
                                            Response::create(
                                                ResponseDescription::create('Unauthorized'),
                                            ),
                                        ),
                                    ),
                                ),
                        ),
                    ),
                'MyEvent',
            );

            expect($callback)->asArray()->toBe([
                '{$request.query.callbackUrl}' => [
                    $method->value => [
                        'requestBody' => [
                            'description' => 'something happened',
                        ],
                        'responses' => [
                            401 => [
                                'description' => 'Unauthorized',
                            ],
                        ],
                    ],
                ],
            ])->name()->toBe('MyEvent');
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
