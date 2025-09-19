<?php

use MohammadAlavi\LaravelOpenApi\Builders\ResponsesBuilder;
use Tests\src\Support\Doubles\Stubs\Attributes\TestResponsesFactory;

describe(class_basename(ResponsesBuilder::class), function (): void {
    it('can be created', function (): void {
        $builder = new ResponsesBuilder();

        $responses = $builder->build(TestResponsesFactory::class);

        expect($responses->compile())->toBe([
            '200' => [
                'description' => 'OK',
            ],
        ]);
    });
})->covers(ResponsesBuilder::class);
