<?php

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Formats\StringFormat;
use MohammadAlavi\ObjectOrientedJSONSchema\v31\Schema;

describe(class_basename(Schema::class), function (): void {
    it(
        'should return string schema with expected format',
        function (StringFormat $stringFormat): void {
            $string = Schema::string()
                ->format($stringFormat)
                ->maxLength(10)
                ->minLength(5)
                ->pattern('^[a-zA-Z0-9]*$');

            expect($string->jsonSerialize())->toBe([
                'type' => 'string',
                'format' => $stringFormat->value,
                'maxLength' => 10,
                'minLength' => 5,
                'pattern' => '^[a-zA-Z0-9]*$',
            ]);
        },
    )->with(
        StringFormat::cases(),
    );
})->covers(Schema::class);
