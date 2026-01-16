<?php

namespace Tests\oooas\Unit\Schema\Objects\Webhooks;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Operation\Operation;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem\PathItem;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem\Support\AvailableOperation;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem\Support\HttpMethod;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response\Response;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Fields\HTTPStatusCode;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Responses;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Support\ResponseEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Webhooks\Fields\Webhook;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Webhooks\Webhooks;

describe(class_basename(Webhooks::class), function (): void {
    it('can create webhooks collection', function (): void {
        $pathItem = PathItem::create()
            ->operations(
                AvailableOperation::create(
                    HttpMethod::POST,
                    Operation::create()
                        ->operationId('newOrder')
                        ->responses(
                            Responses::create(
                                ResponseEntry::create(
                                    HTTPStatusCode::ok(),
                                    Response::create('Webhook received'),
                                ),
                            ),
                        ),
                ),
            );

        $webhooks = Webhooks::create(
            Webhook::create('newOrder', $pathItem),
        );

        expect($webhooks->compile())->toBe([
            'newOrder' => [
                'post' => [
                    'operationId' => 'newOrder',
                    'responses' => [
                        '200' => [
                            'description' => 'Webhook received',
                        ],
                    ],
                ],
            ],
        ]);
    });

    it('can create multiple webhooks', function (): void {
        $orderWebhook = PathItem::create()
            ->operations(
                AvailableOperation::create(
                    HttpMethod::POST,
                    Operation::create()
                        ->operationId('orderCreated')
                        ->summary('Order created webhook')
                        ->responses(
                            Responses::create(
                                ResponseEntry::create(
                                    HTTPStatusCode::ok(),
                                    Response::create('OK'),
                                ),
                            ),
                        ),
                ),
            );

        $paymentWebhook = PathItem::create()
            ->operations(
                AvailableOperation::create(
                    HttpMethod::POST,
                    Operation::create()
                        ->operationId('paymentReceived')
                        ->summary('Payment received webhook')
                        ->responses(
                            Responses::create(
                                ResponseEntry::create(
                                    HTTPStatusCode::ok(),
                                    Response::create('OK'),
                                ),
                            ),
                        ),
                ),
            );

        $webhooks = Webhooks::create(
            Webhook::create('orderCreated', $orderWebhook),
            Webhook::create('paymentReceived', $paymentWebhook),
        );

        $compiled = $webhooks->compile();

        expect($compiled)->toHaveKeys(['orderCreated', 'paymentReceived'])
            ->and($compiled['orderCreated']['post']['operationId'])->toBe('orderCreated')
            ->and($compiled['paymentReceived']['post']['operationId'])->toBe('paymentReceived');
    });

    it('returns empty array when no webhooks', function (): void {
        $webhooks = Webhooks::create();

        expect($webhooks->toArray())->toBe([]);
    });
})->covers(Webhooks::class);

describe(class_basename(Webhook::class), function (): void {
    it('can create webhook entry', function (): void {
        $pathItem = PathItem::create()
            ->summary('Webhook endpoint');

        $webhook = Webhook::create('myWebhook', $pathItem);

        expect($webhook->key())->toBe('myWebhook')
            ->and($webhook->value())->toBe($pathItem);
    });

    it('accepts any valid string as webhook name', function (): void {
        $pathItem = PathItem::create();

        $webhook = Webhook::create('order.created', $pathItem);
        expect($webhook->key())->toBe('order.created');

        $webhook2 = Webhook::create('payment-received', $pathItem);
        expect($webhook2->key())->toBe('payment-received');
    });
})->covers(Webhook::class);
