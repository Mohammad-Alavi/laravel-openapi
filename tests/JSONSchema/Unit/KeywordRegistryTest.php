<?php

use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Vocabulary;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Maximum;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Type;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Vocabularies\CoreVocabulary;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Vocabularies\ValidationVocabulary;
use MohammadAlavi\ObjectOrientedJSONSchema\KeywordRegistry;

describe('KeywordRegistry', function (): void {
    it('can register a vocabulary', function (): void {
        $registry = new KeywordRegistry();
        $vocabulary = new ValidationVocabulary();

        $registry->registerVocabulary($vocabulary);

        expect($registry->hasVocabulary($vocabulary->id()))->toBeTrue();
    });

    it('can register multiple vocabularies', function (): void {
        $registry = new KeywordRegistry();

        $registry->registerVocabularies(
            new CoreVocabulary(),
            new ValidationVocabulary(),
        );

        expect($registry->getVocabularyIds())->toHaveCount(2);
    });

    it('can check if keyword exists', function (): void {
        $registry = new KeywordRegistry();
        $registry->registerVocabulary(new ValidationVocabulary());

        expect($registry->hasKeyword('type'))->toBeTrue();
        expect($registry->hasKeyword('nonexistent'))->toBeFalse();
    });

    it('can get keyword class by name', function (): void {
        $registry = new KeywordRegistry();
        $registry->registerVocabulary(new ValidationVocabulary());

        expect($registry->getKeywordClass('type'))->toBe(Type::class);
        expect($registry->getKeywordClass('maximum'))->toBe(Maximum::class);
        expect($registry->getKeywordClass('nonexistent'))->toBeNull();
    });

    it('can get vocabulary for a keyword', function (): void {
        $registry = new KeywordRegistry();
        $vocab = new ValidationVocabulary();
        $registry->registerVocabulary($vocab);

        $foundVocab = $registry->getVocabularyForKeyword(Type::class);

        expect($foundVocab)->toBeInstanceOf(Vocabulary::class);
        expect($foundVocab->id())->toBe($vocab->id());
    });

    it('can get all keyword names', function (): void {
        $registry = new KeywordRegistry();
        $registry->registerVocabulary(new ValidationVocabulary());

        $names = $registry->getKeywordNames();

        expect($names)->toContain('type');
        expect($names)->toContain('maximum');
        expect($names)->toContain('minimum');
    });

    it('can get required vocabularies', function (): void {
        $registry = new KeywordRegistry();
        $registry->registerVocabularies(
            new CoreVocabulary(),
            new ValidationVocabulary(),
        );

        $required = $registry->getRequiredVocabularies();

        expect($required)->toHaveCount(2);
        foreach ($required as $vocab) {
            expect($vocab->isRequired())->toBeTrue();
        }
    });

    it('can get optional vocabularies', function (): void {
        $registry = new KeywordRegistry();
        $registry->registerVocabularies(
            new CoreVocabulary(),
            new ValidationVocabulary(),
        );

        $optional = $registry->getOptionalVocabularies();

        expect($optional)->toHaveCount(0);
    });

    it('can get vocabulary by id', function (): void {
        $registry = new KeywordRegistry();
        $vocab = new CoreVocabulary();
        $registry->registerVocabulary($vocab);

        $found = $registry->getVocabulary($vocab->id());

        expect($found)->toBe($vocab);
    });

    it('returns null for unknown vocabulary', function (): void {
        $registry = new KeywordRegistry();

        expect($registry->getVocabulary('unknown'))->toBeNull();
    });

    it('can get all vocabularies', function (): void {
        $registry = new KeywordRegistry();
        $registry->registerVocabularies(
            new CoreVocabulary(),
            new ValidationVocabulary(),
        );

        $vocabularies = $registry->getVocabularies();

        expect($vocabularies)->toHaveCount(2);
    });
})->covers(KeywordRegistry::class);
