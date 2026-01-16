<?php

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Draft202012Dialect;
use MohammadAlavi\ObjectOrientedJSONSchema\KeywordRegistry;

describe('Draft202012Dialect', function (): void {
    it('has correct schema URI', function (): void {
        $dialect = new Draft202012Dialect();

        expect($dialect->schemaUri())->toBe('https://json-schema.org/draft/2020-12/schema');
    });

    it('has registry with all vocabularies', function (): void {
        $dialect = new Draft202012Dialect();

        expect($dialect->registry())->toBeInstanceOf(KeywordRegistry::class);
    });

    it('has 7 vocabularies', function (): void {
        $dialect = new Draft202012Dialect();

        expect($dialect->vocabularies())->toHaveCount(7);
    });

    it('supports all standard keywords', function (): void {
        $dialect = new Draft202012Dialect();

        // Core vocabulary
        expect($dialect->supportsKeyword('$id'))->toBeTrue()
            ->and($dialect->supportsKeyword('$schema'))->toBeTrue()
            ->and($dialect->supportsKeyword('$ref'))->toBeTrue()
            // Applicator vocabulary
            ->and($dialect->supportsKeyword('prefixItems'))->toBeTrue()
            ->and($dialect->supportsKeyword('items'))->toBeTrue()
            ->and($dialect->supportsKeyword('contains'))->toBeTrue()
            ->and($dialect->supportsKeyword('patternProperties'))->toBeTrue()
            ->and($dialect->supportsKeyword('dependentSchemas'))->toBeTrue()
            ->and($dialect->supportsKeyword('propertyNames'))->toBeTrue()
            // Validation vocabulary
            ->and($dialect->supportsKeyword('type'))->toBeTrue()
            ->and($dialect->supportsKeyword('maximum'))->toBeTrue()
            // Content vocabulary
            ->and($dialect->supportsKeyword('contentEncoding'))->toBeTrue()
            ->and($dialect->supportsKeyword('contentMediaType'))->toBeTrue()
            ->and($dialect->supportsKeyword('contentSchema'))->toBeTrue();
    });

    it('does not support unknown keywords', function (): void {
        $dialect = new Draft202012Dialect();

        expect($dialect->supportsKeyword('nonexistent'))->toBeFalse();
    });

    it('can get supported keyword names', function (): void {
        $dialect = new Draft202012Dialect();

        $keywords = $dialect->supportedKeywords();

        expect($keywords)->toBeArray()
            ->and($keywords)->toContain('type')
            ->and($keywords)->toContain('prefixItems')
            ->and($keywords)->toContain('contentEncoding');
    });

    it('has required and optional vocabularies', function (): void {
        $dialect = new Draft202012Dialect();

        $required = $dialect->requiredVocabularies();
        $optional = $dialect->optionalVocabularies();

        expect(count($required) + count($optional))->toBe(7);
    });

    it('generates vocabulary declaration', function (): void {
        $dialect = new Draft202012Dialect();

        $declaration = $dialect->vocabularyDeclaration();

        expect($declaration)->toBeArray()
            ->and($declaration)->toHaveKey('https://json-schema.org/draft/2020-12/vocab/core')
            ->and($declaration['https://json-schema.org/draft/2020-12/vocab/core'])->toBeTrue();
    });
})->covers(Draft202012Dialect::class);
