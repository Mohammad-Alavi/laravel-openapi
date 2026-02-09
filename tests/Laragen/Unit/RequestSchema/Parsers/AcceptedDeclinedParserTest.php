<?php

declare(strict_types=1);

use FluentJsonSchema\FluentSchema;
use MohammadAlavi\Laragen\RequestSchema\Parsers\AcceptedDeclinedParser;

describe(class_basename(AcceptedDeclinedParser::class), function (): void {
    it('sets boolean type for accepted rule', function (): void {
        $parser = new AcceptedDeclinedParser();
        $schema = FluentSchema::make();

        $result = $parser('terms', $schema, [['accepted', []]], []);

        $compiled = $result->compile();

        expect($compiled['type'])->toBe('boolean');
    });

    it('sets boolean type for declined rule', function (): void {
        $parser = new AcceptedDeclinedParser();
        $schema = FluentSchema::make();

        $result = $parser('opt_out', $schema, [['declined', []]], []);

        $compiled = $result->compile();

        expect($compiled['type'])->toBe('boolean');
    });

    it('does not modify schema for non-accepted/declined rules', function (): void {
        $parser = new AcceptedDeclinedParser();
        $schema = FluentSchema::make();

        $result = $parser('field', $schema, [['required', []], ['string', []]], []);

        $compiled = $result->compile();

        expect($compiled)->not->toHaveKey('type');
    });
})->covers(AcceptedDeclinedParser::class);
