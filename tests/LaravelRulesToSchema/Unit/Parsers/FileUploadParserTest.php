<?php

use MohammadAlavi\LaravelRulesToSchema\NestedRuleset;
use MohammadAlavi\LaravelRulesToSchema\Parsers\FileUploadParser;
use MohammadAlavi\LaravelRulesToSchema\ValidationRule;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\LooseFluentDescriptor;

describe(class_basename(FileUploadParser::class), function (): void {
    it('sets type string and format binary for file rule', function (): void {
        $parser = new FileUploadParser();
        $schema = LooseFluentDescriptor::withoutSchema();

        $result = $parser('avatar', $schema, [new ValidationRule('file')], new NestedRuleset());

        $compiled = $result->schema()->compile();

        expect($compiled['type'])->toBe('string')
            ->and($compiled['format'])->toBe('binary');
    });

    it('sets type string and format binary for image rule', function (): void {
        $parser = new FileUploadParser();
        $schema = LooseFluentDescriptor::withoutSchema();

        $result = $parser('photo', $schema, [new ValidationRule('image')], new NestedRuleset());

        $compiled = $result->schema()->compile();

        expect($compiled['type'])->toBe('string')
            ->and($compiled['format'])->toBe('binary');
    });

    it('sets type string and format binary for mimes rule', function (): void {
        $parser = new FileUploadParser();
        $schema = LooseFluentDescriptor::withoutSchema();

        $result = $parser('document', $schema, [new ValidationRule('mimes', ['pdf', 'doc'])], new NestedRuleset());

        $compiled = $result->schema()->compile();

        expect($compiled['type'])->toBe('string')
            ->and($compiled['format'])->toBe('binary');
    });

    it('sets type string and format binary for mimetypes rule', function (): void {
        $parser = new FileUploadParser();
        $schema = LooseFluentDescriptor::withoutSchema();

        $result = $parser('upload', $schema, [new ValidationRule('mimetypes', ['application/pdf'])], new NestedRuleset());

        $compiled = $result->schema()->compile();

        expect($compiled['type'])->toBe('string')
            ->and($compiled['format'])->toBe('binary');
    });

    it('does not modify schema for non-file rules', function (): void {
        $parser = new FileUploadParser();
        $schema = LooseFluentDescriptor::withoutSchema();

        $result = $parser('name', $schema, [new ValidationRule('string'), new ValidationRule('required')], new NestedRuleset());

        $compiled = $result->schema()->compile();

        expect($compiled)->not->toHaveKey('format');
    });
})->covers(FileUploadParser::class);
