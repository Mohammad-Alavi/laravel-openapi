<?php

namespace Tests\oooas\Unit\Schema\Objects;

use MohammadAlavi\ObjectOrientedJSONSchema\v31\Schema;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\OneOf;

describe(class_basename(OneOf::class), function (): void {
    it('can be created with all parameters', function (): void {
        $stringBuilder = Schema::string();
        $integerBuilder = Schema::integer();

        $oneOf = OneOf::create('test')
            ->schemas($stringBuilder, $integerBuilder);

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
