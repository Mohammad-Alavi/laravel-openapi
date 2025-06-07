<?php

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Descriptor;

describe(class_basename(Descriptor::class), function (): void {
    it('should return constant value as is', function (mixed $value): void {
        $descriptor = Descriptor::create()->const($value);

        expect($descriptor->jsonSerialize())->toBe([
            '$schema' => 'https://json-schema.org/draft-2020-12/schema',
            'const' => $value,
        ]);
    })->with([
        'test',
        1,
        true,
        null,
        false,
    ]);
})->covers(Descriptor::class);
