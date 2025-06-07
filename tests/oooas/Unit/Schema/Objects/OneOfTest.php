<?php

namespace Tests\oooas\Unit\Schema\Objects;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\OneOf;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;

describe(class_basename(OneOf::class), function (): void {
    it('can be created with all parameters', function (): void {
        $stringDescriptor = Schema::string();
        $integerDescriptor = Schema::integer();

        $oneOf = OneOf::create('test')
            ->schemas($stringDescriptor, $integerDescriptor);

        expect($oneOf->asArray())->toBe([
            'oneOf' => [
                [
                    'type' => 'string',
                ],
                [
                    'type' => 'integer',
                ],
            ],
        ]);
    });
})->covers(OneOf::class);
