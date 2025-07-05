<?php

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Formats\StringFormat;

describe(class_basename(StringFormat::class), function (): void {
    it(
        'returns the correct value for each case',
        function (StringFormat $stringFormat, string $expectedValue): void {
            expect($stringFormat->value())->toBe($expectedValue);
        },
    )->with([
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

    it('has the expected number of cases', function (): void {
        expect(count(StringFormat::cases()))->toBe(20);
    });
})->covers(StringFormat::class);
