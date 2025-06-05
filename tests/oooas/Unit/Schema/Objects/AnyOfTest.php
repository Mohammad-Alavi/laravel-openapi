<?php

namespace Tests\oooas\Unit\Schema\Objects;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\AnyOf;

describe(class_basename(AnyOf::class), function (): void {
    it('can be created with all parameters', function (): void {
        $stringBuilder = Schema::string();
        $integerBuilder = Schema::integer();

        $anyOf = AnyOf::create('test')
            ->schemas($stringBuilder, $integerBuilder);

        expect($anyOf->asArray())->toBe([
            'anyOf' => [
                [
                    'type' => 'string',
                ],
                [
                    'type' => 'integer',
                ],
            ],
        ]);
    });
})->covers(AnyOf::class);
