<?php

namespace Tests\oooas\Unit\Schema\Objects;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\AnyOf;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;

describe(class_basename(AnyOf::class), function (): void {
    it('can be created with all parameters', function (): void {
        $stringDescriptor = Schema::string();
        $integerDescriptor = Schema::integer();

        $anyOf = AnyOf::create('test')
            ->schemas($stringDescriptor, $integerDescriptor);

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
