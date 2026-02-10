<?php

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Concerns\HasGetters;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\JSONSchema;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\LooseFluentDescriptor;

describe(class_basename(HasGetters::class), function (): void {
    it('can get required property names', function (): void {
        $descriptor = LooseFluentDescriptor::withoutSchema()
            ->type('object')
            ->required('name', 'email');

        expect($descriptor->getRequired())->toBe(['name', 'email']);
    });

    it('returns null when required is not set', function (): void {
        $descriptor = LooseFluentDescriptor::withoutSchema()->type('object');

        expect($descriptor->getRequired())->toBeNull();
    });

    it('can get not schema', function (): void {
        $notSchema = LooseFluentDescriptor::withoutSchema()->type('string');
        $descriptor = LooseFluentDescriptor::withoutSchema()->not($notSchema);

        expect($descriptor->getNot())->toBeInstanceOf(JSONSchema::class);
    });

    it('returns null when not is not set', function (): void {
        $descriptor = LooseFluentDescriptor::withoutSchema();

        expect($descriptor->getNot())->toBeNull();
    });

    it('can get if schema', function (): void {
        $ifSchema = LooseFluentDescriptor::withoutSchema()->required('name');
        $descriptor = LooseFluentDescriptor::withoutSchema()->if($ifSchema);

        expect($descriptor->getIf())->toBeInstanceOf(JSONSchema::class);
    });

    it('returns null when if is not set', function (): void {
        $descriptor = LooseFluentDescriptor::withoutSchema();

        expect($descriptor->getIf())->toBeNull();
    });

    it('can get then schema', function (): void {
        $thenSchema = LooseFluentDescriptor::withoutSchema()->required('email');
        $descriptor = LooseFluentDescriptor::withoutSchema()->then($thenSchema);

        expect($descriptor->getThen())->toBeInstanceOf(JSONSchema::class);
    });

    it('returns null when then is not set', function (): void {
        $descriptor = LooseFluentDescriptor::withoutSchema();

        expect($descriptor->getThen())->toBeNull();
    });

    it('can get else schema', function (): void {
        $elseSchema = LooseFluentDescriptor::withoutSchema()->required('phone');
        $descriptor = LooseFluentDescriptor::withoutSchema()->else($elseSchema);

        expect($descriptor->getElse())->toBeInstanceOf(JSONSchema::class);
    });

    it('returns null when else is not set', function (): void {
        $descriptor = LooseFluentDescriptor::withoutSchema();

        expect($descriptor->getElse())->toBeNull();
    });

    it('can get description', function (): void {
        $descriptor = LooseFluentDescriptor::withoutSchema()->description('A test schema');

        expect($descriptor->getDescription())->toBe('A test schema');
    });

    it('returns null when description is not set', function (): void {
        $descriptor = LooseFluentDescriptor::withoutSchema();

        expect($descriptor->getDescription())->toBeNull();
    });

    it('can get pattern', function (): void {
        $descriptor = LooseFluentDescriptor::withoutSchema()->pattern('^[a-z]+$');

        expect($descriptor->getPattern())->toBe('^[a-z]+$');
    });

    it('returns null when pattern is not set', function (): void {
        $descriptor = LooseFluentDescriptor::withoutSchema();

        expect($descriptor->getPattern())->toBeNull();
    });
})->covers(LooseFluentDescriptor::class);
