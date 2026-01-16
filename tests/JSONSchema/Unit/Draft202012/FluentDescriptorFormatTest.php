<?php

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Formats\CustomFormat;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Formats\StringFormat;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\LooseFluentDescriptor;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\StrictFluentDescriptor;

describe('FluentDescriptor format methods', function (): void {
    describe('LooseFluentDescriptor', function (): void {
        it('can set format with StringFormat enum', function (): void {
            $schema = LooseFluentDescriptor::withoutSchema()
                ->type('string')
                ->format(StringFormat::EMAIL);

            $compiled = $schema->compile();

            expect($compiled['format'])->toBe('email');
        });

        it('can set format with raw string', function (): void {
            $schema = LooseFluentDescriptor::withoutSchema()
                ->type('string')
                ->format('phone-number');

            $compiled = $schema->compile();

            expect($compiled['format'])->toBe('phone-number');
        });

        it('can set format with CustomFormat', function (): void {
            $schema = LooseFluentDescriptor::withoutSchema()
                ->type('string')
                ->format(CustomFormat::create('credit-card'));

            $compiled = $schema->compile();

            expect($compiled['format'])->toBe('credit-card');
        });
    });

    describe('StrictFluentDescriptor', function (): void {
        it('can set format with StringFormat enum', function (): void {
            $schema = StrictFluentDescriptor::string()
                ->format(StringFormat::DATE_TIME);

            $compiled = $schema->compile();

            expect($compiled['type'])->toBe('string')
                ->and($compiled['format'])->toBe('date-time');
        });

        it('can set format with raw string', function (): void {
            $schema = StrictFluentDescriptor::string()
                ->format('social-security-number');

            $compiled = $schema->compile();

            expect($compiled['format'])->toBe('social-security-number');
        });

        it('can set format with CustomFormat', function (): void {
            $schema = StrictFluentDescriptor::string()
                ->format(CustomFormat::create('postal-code'));

            $compiled = $schema->compile();

            expect($compiled['format'])->toBe('postal-code');
        });
    });

    describe('all standard StringFormats work', function (): void {
        it('works with all StringFormat cases', function (StringFormat $format, string $expected): void {
            $schema = StrictFluentDescriptor::string()->format($format);

            expect($schema->compile()['format'])->toBe($expected);
        })->with([
            [StringFormat::PASSWORD, 'password'],
            [StringFormat::DATE, 'date'],
            [StringFormat::DATE_TIME, 'date-time'],
            [StringFormat::TIME, 'time'],
            [StringFormat::DURATION, 'duration'],
            [StringFormat::EMAIL, 'email'],
            [StringFormat::IDN_EMAIL, 'idn-email'],
            [StringFormat::HOSTNAME, 'hostname'],
            [StringFormat::IDN_HOSTNAME, 'idn-hostname'],
            [StringFormat::IPV4, 'ipv4'],
            [StringFormat::IPV6, 'ipv6'],
            [StringFormat::URI, 'uri'],
            [StringFormat::URI_REFERENCE, 'uri-reference'],
            [StringFormat::IRI, 'iri'],
            [StringFormat::IRI_REFERENCE, 'iri-reference'],
            [StringFormat::UUID, 'uuid'],
            [StringFormat::URI_TEMPLATE, 'uri-template'],
            [StringFormat::JSON_POINTER, 'json-pointer'],
            [StringFormat::RELATIVE_JSON_POINTER, 'relative-json-pointer'],
            [StringFormat::REGEX, 'regex'],
        ]);
    });

    describe('custom formats with string shorthand', function (): void {
        it('works with common custom format strings', function (string $format): void {
            $schema = StrictFluentDescriptor::string()->format($format);

            expect($schema->compile()['format'])->toBe($format);
        })->with([
            'phone-number',
            'credit-card',
            'social-security-number',
            'postal-code',
            'country-code',
            'currency-code',
            'x-custom-format',
        ]);
    });
})->covers(LooseFluentDescriptor::class, StrictFluentDescriptor::class);
