<?php

namespace Tests\oooas\Unit\Utilities;

use MohammadAlavi\ObjectOrientedOpenAPI\Support\Arr;

describe('Arr', function (): void {
    it('removes null values', function (): void {
        $array = ['test' => null];

        $array = Arr::filter($array);

        expect($array)->toBeEmpty();
    });

    it('keeps non-null values', function (): void {
        $object = new \stdClass();
        $array = [
            'false' => false,
            '0' => 0,
            'string' => 'string',
            'object' => $object,
        ];

        $array = Arr::filter($array);

        expect($array)->toBe([
            'false' => false,
            '0' => 0,
            'string' => 'string',
            'object' => $object,
        ]);
    });

    it('skips specification extensions', function (): void {
        $array = [
            'x-test' => null,
        ];

        $array = Arr::filter($array);

        expect($array)->toBe([
            'x-test' => null,
        ]);
    });
})->covers(Arr::class);
