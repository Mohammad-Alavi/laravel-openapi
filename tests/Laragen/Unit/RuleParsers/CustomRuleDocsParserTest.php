<?php

declare(strict_types=1);

use FluentJsonSchema\FluentSchema;
use MohammadAlavi\Laragen\RuleParsers\CustomRuleDocsParser;
use Tests\Laragen\Support\Doubles\Rules\DocumentedRule;
use Tests\Laragen\Support\Doubles\Rules\EnumDocumentedRule;
use Tests\Laragen\Support\Doubles\Rules\UndocumentedRule;

describe(class_basename(CustomRuleDocsParser::class), function (): void {
    it('applies type and format from docs() method', function (): void {
        $parser = new CustomRuleDocsParser();
        $schema = FluentSchema::make();
        $rule = new DocumentedRule();

        $result = $parser('field', $schema, [[$rule, []]], []);

        expect($result)->toBeInstanceOf(FluentSchema::class);

        $compiled = $result->compile();

        expect($compiled['type'])->toBe('string')
            ->and($compiled['format'])->toBe('date-time')
            ->and($compiled['description'])->toBe('A valid datetime string');
    });

    it('applies enum values from docs() method', function (): void {
        $parser = new CustomRuleDocsParser();
        $schema = FluentSchema::make();
        $rule = new EnumDocumentedRule();

        $result = $parser('status', $schema, [[$rule, []]], []);

        expect($result)->toBeInstanceOf(FluentSchema::class);

        $compiled = $result->compile();

        expect($compiled['type'])->toBe('string')
            ->and($compiled['enum'])->toBe(['active', 'inactive', 'pending']);
    });

    it('skips rules without docs() method', function (): void {
        $parser = new CustomRuleDocsParser();
        $schema = FluentSchema::make();
        $rule = new UndocumentedRule();

        $result = $parser('field', $schema, [[$rule, []]], []);

        expect($result)->toBeInstanceOf(FluentSchema::class);

        $compiled = $result->compile();

        expect($compiled)->toBe([]);
    });

    it('skips string rules', function (): void {
        $parser = new CustomRuleDocsParser();
        $schema = FluentSchema::make();

        $result = $parser('field', $schema, [['required', []]], []);

        expect($result)->toBeInstanceOf(FluentSchema::class);

        $compiled = $result->compile();

        expect($compiled)->toBe([]);
    });
})->covers(CustomRuleDocsParser::class);
