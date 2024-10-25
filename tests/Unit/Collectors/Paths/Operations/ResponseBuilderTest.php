<?php

use Illuminate\Support\Facades\Route;
use MohammadAlavi\LaravelOpenApi\Attributes\Responses as ResponsesAttribute;
use MohammadAlavi\LaravelOpenApi\Builders\Paths\Operation\ResponsesBuilder;
use MohammadAlavi\LaravelOpenApi\Objects\RouteInfo;
use Tests\Doubles\Stubs\Attributes\ResponsesFactory;

describe(class_basename(ResponsesBuilder::class), function (): void {
    it('can be created', function (): void {
        $routeInformation = RouteInfo::create(
            Route::get('/example', static fn (): string => 'example'),
        );
        $routeInformation->actionAttributes = collect([
            new ResponsesAttribute(ResponsesFactory::class),
        ]);
        $builder = new ResponsesBuilder();

        $result = $builder->build($routeInformation->responsesAttribute());

        expect($result->asArray())->toBe([
            '200' => [
                'description' => 'OK',
            ],
        ]);
    });
})->covers(ResponsesBuilder::class);
