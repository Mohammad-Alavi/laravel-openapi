<?php

declare(strict_types=1);

use FluentJsonSchema\FluentSchema;
use MohammadAlavi\Laragen\RequestSchema\Parsers\FileUploadParser;

describe(class_basename(FileUploadParser::class), function (): void {
    it('sets type string and format binary for file rule', function (): void {
        $parser = new FileUploadParser();
        $schema = FluentSchema::make();

        $result = $parser('avatar', $schema, [['file', []]], []);

        $compiled = $result->compile();

        expect($compiled['type'])->toBe('string')
            ->and($compiled['format'])->toBe('binary');
    });

    it('sets type string and format binary for image rule', function (): void {
        $parser = new FileUploadParser();
        $schema = FluentSchema::make();

        $result = $parser('photo', $schema, [['image', []]], []);

        $compiled = $result->compile();

        expect($compiled['type'])->toBe('string')
            ->and($compiled['format'])->toBe('binary');
    });

    it('sets type string and format binary for mimes rule', function (): void {
        $parser = new FileUploadParser();
        $schema = FluentSchema::make();

        $result = $parser('document', $schema, [['mimes', ['pdf', 'doc']]], []);

        $compiled = $result->compile();

        expect($compiled['type'])->toBe('string')
            ->and($compiled['format'])->toBe('binary');
    });

    it('sets type string and format binary for mimetypes rule', function (): void {
        $parser = new FileUploadParser();
        $schema = FluentSchema::make();

        $result = $parser('upload', $schema, [['mimetypes', ['application/pdf']]], []);

        $compiled = $result->compile();

        expect($compiled['type'])->toBe('string')
            ->and($compiled['format'])->toBe('binary');
    });

    it('does not modify schema for non-file rules', function (): void {
        $parser = new FileUploadParser();
        $schema = FluentSchema::make();

        $result = $parser('name', $schema, [['string', []], ['required', []]], []);

        $compiled = $result->compile();

        expect($compiled)->not->toHaveKey('format');
    });
})->covers(FileUploadParser::class);
