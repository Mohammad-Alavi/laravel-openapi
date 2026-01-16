<?php

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\BooleanSchema;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\JSONSchema;

describe('BooleanSchema', function (): void {
    it('implements JSONSchema interface', function (): void {
        $schema = BooleanSchema::true();

        expect($schema)->toBeInstanceOf(JSONSchema::class);
    });

    it('can create true schema', function (): void {
        $schema = BooleanSchema::true();

        expect($schema->value())->toBeTrue()
            ->and($schema->isTrue())->toBeTrue()
            ->and($schema->isFalse())->toBeFalse();
    });

    it('can create false schema', function (): void {
        $schema = BooleanSchema::false();

        expect($schema->value())->toBeFalse()
            ->and($schema->isTrue())->toBeFalse()
            ->and($schema->isFalse())->toBeTrue();
    });

    it('can create from boolean value', function (): void {
        $trueSchema = BooleanSchema::create(true);
        $falseSchema = BooleanSchema::create(false);

        expect($trueSchema->value())->toBeTrue()
            ->and($falseSchema->value())->toBeFalse();
    });

    it('serializes true to boolean true', function (): void {
        $schema = BooleanSchema::true();

        expect($schema->jsonSerialize())->toBeTrue()
            ->and(json_encode($schema))->toBe('true');
    });

    it('serializes false to boolean false', function (): void {
        $schema = BooleanSchema::false();

        expect($schema->jsonSerialize())->toBeFalse()
            ->and(json_encode($schema))->toBe('false');
    });
})->covers(BooleanSchema::class);
