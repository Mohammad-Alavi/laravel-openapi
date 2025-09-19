<?php

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Generatable;
use Tests\oooas\Support\Doubles\Fakes\GeneratableFake;

describe(class_basename(Generatable::class), function (): void {
    it('can be json serializable', function (): void {
        expect(Generatable::class)->toImplement(JsonSerializable::class);

        $object = new GeneratableFake();

        $result = $object->compile();

        expect($result)->toBe([]);
    });
})->covers(Generatable::class);
