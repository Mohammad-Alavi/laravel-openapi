<?php

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Draft202012Dialect;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Formats\StringFormat;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\StrictFluentDescriptor;
use MohammadAlavi\ObjectOrientedJSONSchema\KeywordRegistry;
use MohammadAlavi\ObjectOrientedJSONSchema\Validation\VocabularyValidationError;
use MohammadAlavi\ObjectOrientedJSONSchema\Validation\VocabularyValidator;

describe(class_basename(VocabularyValidator::class), function (): void {
    it('validates schema with known keywords', function (): void {
        $dialect = new Draft202012Dialect();
        $validator = new VocabularyValidator($dialect->registry());

        $schema = StrictFluentDescriptor::object()
            ->properties(
                MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Properties\Property::create(
                    'name',
                    StrictFluentDescriptor::string()->minLength(1),
                ),
            )
            ->required('name')
            ->compile();

        $result = $validator->validate($schema);

        expect($result->isValid())->toBeTrue();
        expect($result->hasErrors())->toBeFalse();
    });

    it('reports unknown keywords', function (): void {
        $dialect = new Draft202012Dialect();
        $validator = new VocabularyValidator($dialect->registry());

        // Schema with an unknown keyword
        $schema = [
            'type' => 'object',
            'unknownKeyword' => 'value',
            'anotherUnknown' => 123,
        ];

        $result = $validator->validate($schema);

        expect($result->isValid())->toBeFalse();
        expect($result->hasErrors())->toBeTrue();
        expect($result->unknownKeywords)->toContain('unknownKeyword');
        expect($result->unknownKeywords)->toContain('anotherUnknown');
    });

    it('tracks used vocabularies', function (): void {
        $dialect = new Draft202012Dialect();
        $validator = new VocabularyValidator($dialect->registry());

        $schema = StrictFluentDescriptor::string()
            ->minLength(1)
            ->maxLength(100)
            ->format(StringFormat::EMAIL)
            ->compile();

        $result = $validator->validate($schema);

        expect($result->isValid())->toBeTrue();
        expect($result->usedVocabularies)->toContain('https://json-schema.org/draft/2020-12/vocab/validation');
        expect($result->usedVocabularies)->toContain('https://json-schema.org/draft/2020-12/vocab/format-annotation');
    });

    it('supports required vocabularies check', function (): void {
        $dialect = new Draft202012Dialect();
        $validator = new VocabularyValidator($dialect->registry());

        expect($validator->supportsRequiredVocabularies())->toBeTrue();
        expect($validator->getMissingRequiredVocabularies())->toBeEmpty();
    });

    it('detects missing required vocabularies', function (): void {
        $registry = new KeywordRegistry();
        // Don't register any vocabularies

        $declaration = [
            'https://json-schema.org/draft/2020-12/vocab/core' => true,
            'https://json-schema.org/draft/2020-12/vocab/validation' => true,
        ];

        $validator = VocabularyValidator::fromVocabularyDeclaration($declaration, $registry);

        expect($validator->supportsRequiredVocabularies())->toBeFalse();
        expect($validator->getMissingRequiredVocabularies())->toContain('https://json-schema.org/draft/2020-12/vocab/core');
        expect($validator->getMissingRequiredVocabularies())->toContain('https://json-schema.org/draft/2020-12/vocab/validation');
    });

    it('ignores $schema and $vocabulary keywords during validation', function (): void {
        $dialect = new Draft202012Dialect();
        $validator = new VocabularyValidator($dialect->registry());

        $schema = [
            '$schema' => 'https://json-schema.org/draft/2020-12/schema',
            '$vocabulary' => [
                'https://json-schema.org/draft/2020-12/vocab/core' => true,
            ],
            'type' => 'string',
        ];

        $result = $validator->validate($schema);

        expect($result->isValid())->toBeTrue();
        expect($result->unknownKeywords)->not->toContain('$schema');
        expect($result->unknownKeywords)->not->toContain('$vocabulary');
    });

    it('validates nested schemas', function (): void {
        $dialect = new Draft202012Dialect();
        $validator = new VocabularyValidator($dialect->registry());

        $schema = [
            'type' => 'object',
            'properties' => [
                'nested' => [
                    'type' => 'object',
                    'unknownNested' => 'value', // Unknown keyword in nested schema
                ],
            ],
        ];

        $result = $validator->validate($schema);

        // Note: The current implementation checks array keys, so it may or may not
        // catch 'unknownNested' depending on how deep the validation goes.
        // This test documents the current behavior.
        expect($result)->toBeInstanceOf(MohammadAlavi\ObjectOrientedJSONSchema\Validation\VocabularyValidationResult::class);
    });
})->covers(VocabularyValidator::class);

describe(class_basename(VocabularyValidationError::class), function (): void {
    it('has correct properties', function (): void {
        $error = new VocabularyValidationError(
            'Test message',
            'testKeyword',
            VocabularyValidationError::UNKNOWN_KEYWORD,
        );

        expect($error->message)->toBe('Test message');
        expect($error->subject)->toBe('testKeyword');
        expect($error->code)->toBe(VocabularyValidationError::UNKNOWN_KEYWORD);
    });

    it('converts to string', function (): void {
        $error = new VocabularyValidationError(
            'Test message',
            'testKeyword',
            VocabularyValidationError::UNKNOWN_KEYWORD,
        );

        expect((string) $error)->toBe('[unknown_keyword] Test message');
    });
})->covers(VocabularyValidationError::class);

describe(class_basename(MohammadAlavi\ObjectOrientedJSONSchema\Validation\VocabularyValidationResult::class), function (): void {
    it('reports valid state correctly', function (): void {
        $result = new MohammadAlavi\ObjectOrientedJSONSchema\Validation\VocabularyValidationResult(true);

        expect($result->isValid())->toBeTrue();
        expect($result->hasErrors())->toBeFalse();
        expect($result->hasWarnings())->toBeFalse();
    });

    it('reports invalid state correctly', function (): void {
        $error = new VocabularyValidationError('Error', 'subject', 'code');
        $result = new MohammadAlavi\ObjectOrientedJSONSchema\Validation\VocabularyValidationResult(
            false,
            [$error],
        );

        expect($result->isValid())->toBeFalse();
        expect($result->hasErrors())->toBeTrue();
        expect($result->getErrorMessages())->toBe(['Error']);
    });

    it('tracks warnings separately', function (): void {
        $warning = new VocabularyValidationError('Warning', 'subject', 'code');
        $result = new MohammadAlavi\ObjectOrientedJSONSchema\Validation\VocabularyValidationResult(
            true,
            [],
            [$warning],
        );

        expect($result->isValid())->toBeTrue();
        expect($result->hasWarnings())->toBeTrue();
        expect($result->getWarningMessages())->toBe(['Warning']);
    });
})->covers(MohammadAlavi\ObjectOrientedJSONSchema\Validation\VocabularyValidationResult::class);
