<?php

use Illuminate\Support\Facades\Route;
use MohammadAlavi\LaravelOpenApi\Attributes\Responses as ResponsesAttribute;
use MohammadAlavi\LaravelOpenApi\Builders\ResponsesBuilder;
use MohammadAlavi\LaravelOpenApi\Support\RouteInfo;
use Tests\src\Support\Doubles\Stubs\Attributes\TestResponsesFactory;

describe(class_basename(ResponsesBuilder::class), function (): void {
    it('can be created', function (): void {
        $routeInformation = RouteInfo::create(
            Route::get('/example', static fn (): string => 'example'),
        );
        $routeInformation->actionAttributes = collect([
            new ResponsesAttribute(TestResponsesFactory::class),
        ]);
        $builder = new ResponsesBuilder();

        $responses = $builder->build($routeInformation->responsesAttribute());

        expect($responses->unserializeToArray())->toBe([
            '200' => [
                'description' => 'OK',
            ],
        ]);
    });
})->covers(ResponsesBuilder::class);
