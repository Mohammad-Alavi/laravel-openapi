<?php

declare(strict_types=1);

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Defs\Def;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Defs\Defs;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\LooseFluentDescriptor;

describe(class_basename(Defs::class), function (): void {
    it('can be created with multiple Defs and returns correct values', function (): void {
        $desc1 = LooseFluentDescriptor::create('string');
        $desc2 = LooseFluentDescriptor::create('integer');
        $def1 = Def::create('foo', $desc1);
        $def2 = Def::create('bar', $desc2);
        $defs = Defs::create($def1, $def2);

        expect($defs->value())->toBe([$def1, $def2])
            ->and($defs->jsonSerialize())->toBe([
                'foo' => $desc1,
                'bar' => $desc2,
            ]);
    });

    it('returns the correct name', function (): void {
        expect(Defs::name())->toBe('$defs');
    });

    it('is immutable', function (): void {
        expect(Defs::class)->toBeImmutable();
    });

    it('can be created with no defs', function (): void {
        $defs = Defs::create();

        expect($defs->value())->toBe([])
            ->and($defs->jsonSerialize())->toBe([]);
    });
})->covers(Defs::class);
