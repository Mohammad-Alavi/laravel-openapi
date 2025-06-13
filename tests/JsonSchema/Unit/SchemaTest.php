<?php

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Formats\StringFormat;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;

describe(class_basename(Schema::class), function (): void {
    it(
        'should return string schema with expected format',
        function (StringFormat $stringFormat): void {
            $stringDescriptor = Schema::string()
                ->format($stringFormat)
                ->maxLength(10)
                ->minLength(5)
                ->pattern('^[a-zA-Z0-9]*$');

            expect(\Safe\json_encode($stringDescriptor))->toBe(
                \Safe\json_encode([
                    'type' => 'string',
                    'format' => $stringFormat->value,
                    'maxLength' => 10,
                    'minLength' => 5,
                    'pattern' => '^[a-zA-Z0-9]*$',
                ]),
            );
        },
    )->with(
        StringFormat::cases(),
    );
})->covers(Schema::class);
