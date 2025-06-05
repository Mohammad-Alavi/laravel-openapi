<?php

namespace Tests\oooas\Unit\Schema\Objects;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Not;

describe(class_basename(Not::class), function (): void {
    it('can be created with no parameters', function (): void {
        $not = Not::create();

        expect($not->asArray())->toBeEmpty();
    });

    it('can be created with all parameters', function (): void {
        $not = Not::create()
            ->schema(Schema::string());

        expect($not->asArray())->toBe([
            'not' => [
                'type' => 'string',
            ],
        ]);
    });
})->covers(Not::class);
