<?php

use MohammadAlavi\LaravelOpenApi\oooas\Schema\BaseObject;
use Tests\oooas\Doubles\Fakes\BaseObjectFake;

describe('BaseObject', function (): void {
    it('can be statically created', function (string|null $objectId, array $expectation): void {
        $baseObjectFake = BaseObjectFake::create($objectId);

        expect($baseObjectFake)->toBeInstanceOf(BaseObject::class)
            ->and($baseObjectFake->jsonSerialize())->toBe($expectation);
    })->with([
        'null' => [null, []],
        'empty' => ['', ['objectId' => '']],
        'test' => ['test', ['objectId' => 'test']],
    ]);

    it('can be statically created with ref method', function (): void {
        $baseObjectFake = BaseObjectFake::ref('test');

        $result = $baseObjectFake->ref;

        expect($result)->toBe('test');
    });

    it('can be json serializable', function (): void {
        expect(BaseObject::class)->toImplement(JsonSerializable::class);

        $object = BaseObjectFake::create();

        $result = $object->jsonSerialize();

        expect($result)->toBe([]);

        $object = BaseObjectFake::ref('test');

        $result = $object->jsonSerialize();

        expect($result)->toBe(['$ref' => 'test']);
    });
})->covers(BaseObject::class);
