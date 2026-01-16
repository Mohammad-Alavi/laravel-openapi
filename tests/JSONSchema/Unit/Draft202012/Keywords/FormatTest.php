<?php

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Formats\CustomFormat;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Formats\StringFormat;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Format;

describe(class_basename(Format::class), function (): void {
    it('has correct keyword name', function (): void {
        expect(Format::name())->toBe('format');
    });

    it('can create from StringFormat enum', function (): void {
        $format = Format::create(StringFormat::EMAIL);

        expect($format->value())->toBe('email');
        expect($format->jsonSerialize())->toBe('email');
    });

    it('can create from CustomFormat', function (): void {
        $format = Format::create(CustomFormat::create('phone-number'));

        expect($format->value())->toBe('phone-number');
    });

    it('can create from raw string', function (): void {
        $format = Format::create('credit-card');

        expect($format->value())->toBe('credit-card');
        expect($format->jsonSerialize())->toBe('credit-card');
    });

    it('returns DefinedFormat via format() method', function (): void {
        $stringFormat = Format::create(StringFormat::UUID);
        $customFormat = Format::create('my-format');

        expect($stringFormat->format())->toBe(StringFormat::UUID);
        expect($customFormat->format())->toBeInstanceOf(CustomFormat::class);
    });

    it('creates CustomFormat when given string', function (): void {
        $format = Format::create('phone-number');

        expect($format->format())->toBeInstanceOf(CustomFormat::class);
        expect($format->format()->value())->toBe('phone-number');
    });

    it('works with all StringFormat cases', function (StringFormat $stringFormat): void {
        $format = Format::create($stringFormat);

        expect($format->value())->toBe($stringFormat->value());
    })->with([
        StringFormat::DATE,
        StringFormat::DATE_TIME,
        StringFormat::TIME,
        StringFormat::EMAIL,
        StringFormat::URI,
        StringFormat::UUID,
        StringFormat::HOSTNAME,
        StringFormat::IPV4,
        StringFormat::IPV6,
    ]);
})->covers(Format::class);
