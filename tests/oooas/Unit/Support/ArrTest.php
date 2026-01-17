<?php

namespace Tests\oooas\Unit\Support;

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

    it('serializes JsonSerializable objects', function (): void {
        $jsonSerializable = new class implements \JsonSerializable {
            public function jsonSerialize(): array
            {
                return ['nested' => 'value', 'null_field' => null];
            }
        };

        $array = Arr::filter([
            'serializable' => $jsonSerializable,
            'regular' => 'value',
        ]);

        expect($array)->toBe([
            'serializable' => ['nested' => 'value'],
            'regular' => 'value',
        ]);
    });

    it('recursively processes nested JsonSerializable objects', function (): void {
        $inner = new class implements \JsonSerializable {
            public function jsonSerialize(): array
            {
                return ['inner_key' => 'inner_value'];
            }
        };

        $outer = new class($inner) implements \JsonSerializable {
            public function __construct(private \JsonSerializable $inner)
            {
            }

            public function jsonSerialize(): array
            {
                return ['outer_key' => $this->inner, 'null_field' => null];
            }
        };

        $array = Arr::filter([
            'nested' => $outer,
        ]);

        expect($array)->toBe([
            'nested' => [
                'outer_key' => ['inner_key' => 'inner_value'],
            ],
        ]);
    });
})->covers(Arr::class);
