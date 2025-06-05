<?php

namespace Tests\oooas\Unit\Schema\Objects;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\AllOf;

describe(class_basename(AllOf::class), function (): void {
    it('can be created', function (): void {
        $stringBuilder = Schema::string();
        $integerBuilder = Schema::integer();

        $allOf = AllOf::create('test')
            ->schemas($stringBuilder, $integerBuilder);

        expect($allOf->asArray())->toBe([
            'allOf' => [
                [
                    'type' => 'string',
                ],
                [
                    'type' => 'integer',
                ],
            ],
        ]);
    });
})->covers(AllOf::class);
