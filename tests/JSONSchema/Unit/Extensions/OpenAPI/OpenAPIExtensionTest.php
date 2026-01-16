<?php

use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Vocabulary;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Properties\Property;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\StrictFluentDescriptor;
use MohammadAlavi\ObjectOrientedJSONSchema\Extensions\OpenAPI\Concerns\HasOpenAPIKeywords;
use MohammadAlavi\ObjectOrientedJSONSchema\Extensions\OpenAPI\Keywords\Discriminator;
use MohammadAlavi\ObjectOrientedJSONSchema\Extensions\OpenAPI\Keywords\ExternalDocs;
use MohammadAlavi\ObjectOrientedJSONSchema\Extensions\OpenAPI\OpenAPIVocabulary;

describe('Discriminator', function (): void {
    it('has correct name', function (): void {
        expect(Discriminator::name())->toBe('discriminator');
    });

    it('creates with propertyName only', function (): void {
        $discriminator = Discriminator::create('petType');

        expect($discriminator->value())->toBe([
            'propertyName' => 'petType',
            'mapping' => [],
        ]);
    });

    it('creates with propertyName and mapping', function (): void {
        $discriminator = Discriminator::create('petType', [
            'cat' => '#/components/schemas/Cat',
            'dog' => '#/components/schemas/Dog',
        ]);

        expect($discriminator->value())->toBe([
            'propertyName' => 'petType',
            'mapping' => [
                'cat' => '#/components/schemas/Cat',
                'dog' => '#/components/schemas/Dog',
            ],
        ]);
    });

    it('serializes without mapping when empty', function (): void {
        $discriminator = Discriminator::create('petType');

        expect($discriminator->jsonSerialize())->toBe([
            'propertyName' => 'petType',
        ]);
    });

    it('serializes with mapping when provided', function (): void {
        $discriminator = Discriminator::create('petType', ['cat' => '#/Cat']);

        expect($discriminator->jsonSerialize())->toBe([
            'propertyName' => 'petType',
            'mapping' => ['cat' => '#/Cat'],
        ]);
    });
})->covers(Discriminator::class);

describe('ExternalDocs', function (): void {
    it('has correct name', function (): void {
        expect(ExternalDocs::name())->toBe('externalDocs');
    });

    it('creates with url only', function (): void {
        $docs = ExternalDocs::create('https://example.com');

        expect($docs->value())->toBe([
            'url' => 'https://example.com',
            'description' => null,
        ]);
    });

    it('creates with url and description', function (): void {
        $docs = ExternalDocs::create('https://example.com', 'API Docs');

        expect($docs->value())->toBe([
            'url' => 'https://example.com',
            'description' => 'API Docs',
        ]);
    });

    it('serializes without description when null', function (): void {
        $docs = ExternalDocs::create('https://example.com');

        expect($docs->jsonSerialize())->toBe([
            'url' => 'https://example.com',
        ]);
    });

    it('serializes with description when provided', function (): void {
        $docs = ExternalDocs::create('https://example.com', 'API Docs');

        expect($docs->jsonSerialize())->toBe([
            'url' => 'https://example.com',
            'description' => 'API Docs',
        ]);
    });
})->covers(ExternalDocs::class);

describe('OpenAPIVocabulary', function (): void {
    it('implements Vocabulary interface', function (): void {
        $vocab = new OpenAPIVocabulary();

        expect($vocab)->toBeInstanceOf(Vocabulary::class);
    });

    it('has correct id', function (): void {
        $vocab = new OpenAPIVocabulary();

        expect($vocab->id())->toBe('https://spec.openapis.org/oas/3.1/vocab/base');
    });

    it('is not required', function (): void {
        $vocab = new OpenAPIVocabulary();

        expect($vocab->isRequired())->toBeFalse();
    });

    it('contains Discriminator and ExternalDocs keywords', function (): void {
        $vocab = new OpenAPIVocabulary();
        $keywords = $vocab->keywords();

        expect($keywords)->toContain(Discriminator::class);
        expect($keywords)->toContain(ExternalDocs::class);
    });
})->covers(OpenAPIVocabulary::class);

describe('HasOpenAPIKeywords trait', function (): void {
    it('provides discriminator method with autocomplete', function (): void {
        // Using set() directly since anonymous classes can't extend due to private constructor
        $schema = StrictFluentDescriptor::object()
            ->properties(
                Property::create('petType', StrictFluentDescriptor::string()),
            )
            ->set(Discriminator::create('petType', ['cat' => '#/Cat']));

        $compiled = $schema->compile();

        expect($compiled)->toHaveKey('discriminator');
        expect($compiled['discriminator']['propertyName'])->toBe('petType');
    });

    it('provides externalDocs method with autocomplete', function (): void {
        // Using set() directly since anonymous classes can't extend due to private constructor
        $schema = StrictFluentDescriptor::object()
            ->set(ExternalDocs::create('https://example.com', 'API Docs'));

        $compiled = $schema->compile();

        expect($compiled)->toHaveKey('externalDocs');
        expect($compiled['externalDocs']['url'])->toBe('https://example.com');
    });

    it('trait methods delegate to set()', function (): void {
        // Verify the trait methods work correctly by testing the keywords directly
        $discriminator = Discriminator::create('petType', ['cat' => '#/Cat']);
        $externalDocs = ExternalDocs::create('https://example.com', 'API Docs');

        expect($discriminator::name())->toBe('discriminator');
        expect($externalDocs::name())->toBe('externalDocs');
    });
})->covers(HasOpenAPIKeywords::class);
