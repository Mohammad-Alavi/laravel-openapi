<?php

namespace Tests\oooas\Unit\Support\SharedFields;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\MediaType\MediaType;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Content\ContentEntry;

describe(class_basename(ContentEntry::class), function (): void {
    describe('existing methods', function (): void {
        it('can create json content', function (): void {
            $entry = ContentEntry::json(MediaType::create());

            expect($entry->key())->toBe('application/json');
        });

        it('can create pdf content', function (): void {
            $entry = ContentEntry::pdf(MediaType::create());

            expect($entry->key())->toBe('application/pdf');
        });

        it('can create xml content', function (): void {
            $entry = ContentEntry::xml(MediaType::create());

            expect($entry->key())->toBe('text/xml');
        });

        it('can create form url encoded content', function (): void {
            $entry = ContentEntry::formUrlEncoded(MediaType::create());

            expect($entry->key())->toBe('application/x-www-form-urlencoded');
        });

        it('can create plain text content', function (): void {
            $entry = ContentEntry::plainText(MediaType::create());

            expect($entry->key())->toBe('text/plain');
        });

        it('can create jpeg content', function (): void {
            $entry = ContentEntry::jpeg(MediaType::create());

            expect($entry->key())->toBe('image/jpeg');
        });

        it('can create png content', function (): void {
            $entry = ContentEntry::png(MediaType::create());

            expect($entry->key())->toBe('image/png');
        });

        it('can create calendar content', function (): void {
            $entry = ContentEntry::calendar(MediaType::create());

            expect($entry->key())->toBe('text/calendar');
        });
    });

    describe('new methods', function (): void {
        it('can create multipart form data content', function (): void {
            $entry = ContentEntry::multipartFormData(MediaType::create());

            expect($entry->key())->toBe('multipart/form-data');
        });

        it('can create octet stream content', function (): void {
            $entry = ContentEntry::octetStream(MediaType::create());

            expect($entry->key())->toBe('application/octet-stream');
        });

        it('can create html content', function (): void {
            $entry = ContentEntry::html(MediaType::create());

            expect($entry->key())->toBe('text/html');
        });

        it('can create csv content', function (): void {
            $entry = ContentEntry::csv(MediaType::create());

            expect($entry->key())->toBe('text/csv');
        });

        it('can create gif content', function (): void {
            $entry = ContentEntry::gif(MediaType::create());

            expect($entry->key())->toBe('image/gif');
        });

        it('can create svg content', function (): void {
            $entry = ContentEntry::svg(MediaType::create());

            expect($entry->key())->toBe('image/svg+xml');
        });

        it('can create webp content', function (): void {
            $entry = ContentEntry::webp(MediaType::create());

            expect($entry->key())->toBe('image/webp');
        });

        it('can create zip content', function (): void {
            $entry = ContentEntry::zip(MediaType::create());

            expect($entry->key())->toBe('application/zip');
        });

        it('can create yaml content', function (): void {
            $entry = ContentEntry::yaml(MediaType::create());

            expect($entry->key())->toBe('application/yaml');
        });

        it('can create any content type', function (): void {
            $entry = ContentEntry::any(MediaType::create());

            expect($entry->key())->toBe('*/*');
        });
    });

    describe('custom content type', function (): void {
        it('can create custom content type', function (): void {
            $entry = ContentEntry::create('application/vnd.api+json', MediaType::create());

            expect($entry->key())->toBe('application/vnd.api+json');
        });
    });
})->covers(ContentEntry::class);
