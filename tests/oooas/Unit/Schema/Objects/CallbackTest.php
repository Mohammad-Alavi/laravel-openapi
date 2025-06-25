<?php

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Callback;
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

describe(class_basename(Callback::class), function (): void {
    it('can be created with no parameters', function (): void {
        $callback = Callback::create();

        expect($callback->asArray())->toBeEmpty();
    });

    it(
        'can be created with all parameters',
        function (HttpMethod $method): void {
            $callback = Callback::create(
                'MyEvent',
                '{$request.query.callbackUrl}',
                PathItem::create()
                    ->operations(
                        AvailableOperation::create(
                            $method,
                            Operation::create()
                                ->requestBody(
                                    RequestBody::create()
                                        ->description(Description::create('something happened')),
                                )->responses(
                                    Responses::create(
                                        ResponseEntry::create(
                                            HTTPStatusCode::unauthorized(),
                                            Response::create(ResponseDescription::create('Unauthorized')),
                                        ),
                                    ),
                                ),
                        ),
                    ),
            );

            expect($callback->asArray())->toBe([
                'operationRef' => 'testRef',
                'operationId' => 'testId',
                'description' => 'Some descriptions',
                'server' => $callback->asArray(),
            ]);
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
})->covers(Callback::class)->skip();
