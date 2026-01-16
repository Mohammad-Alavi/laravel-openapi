<?php

use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Vocabulary;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Vocabularies\ApplicatorVocabulary;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Vocabularies\ContentVocabulary;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Vocabularies\CoreVocabulary;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Vocabularies\FormatAnnotationVocabulary;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Vocabularies\MetaDataVocabulary;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Vocabularies\UnevaluatedVocabulary;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Vocabularies\ValidationVocabulary;

describe('CoreVocabulary', function (): void {
    it('implements Vocabulary interface', function (): void {
        $vocab = new CoreVocabulary();

        expect($vocab)->toBeInstanceOf(Vocabulary::class);
    });

    it('has correct id', function (): void {
        $vocab = new CoreVocabulary();

        expect($vocab->id())->toBe('https://json-schema.org/draft/2020-12/vocab/core');
    });

    it('is required', function (): void {
        $vocab = new CoreVocabulary();

        expect($vocab->isRequired())->toBeTrue();
    });

    it('returns array of keyword classes', function (): void {
        $vocab = new CoreVocabulary();
        $keywords = $vocab->keywords();

        expect($keywords)->toBeArray();
        expect($keywords)->not->toBeEmpty();
    });
})->covers(CoreVocabulary::class);

describe('ValidationVocabulary', function (): void {
    it('implements Vocabulary interface', function (): void {
        $vocab = new ValidationVocabulary();

        expect($vocab)->toBeInstanceOf(Vocabulary::class);
    });

    it('has correct id', function (): void {
        $vocab = new ValidationVocabulary();

        expect($vocab->id())->toBe('https://json-schema.org/draft/2020-12/vocab/validation');
    });

    it('is required', function (): void {
        $vocab = new ValidationVocabulary();

        expect($vocab->isRequired())->toBeTrue();
    });

    it('returns array of keyword classes', function (): void {
        $vocab = new ValidationVocabulary();
        $keywords = $vocab->keywords();

        expect($keywords)->toBeArray();
        expect($keywords)->not->toBeEmpty();
    });
})->covers(ValidationVocabulary::class);

describe('ApplicatorVocabulary', function (): void {
    it('implements Vocabulary interface', function (): void {
        $vocab = new ApplicatorVocabulary();

        expect($vocab)->toBeInstanceOf(Vocabulary::class);
    });

    it('has correct id', function (): void {
        $vocab = new ApplicatorVocabulary();

        expect($vocab->id())->toBe('https://json-schema.org/draft/2020-12/vocab/applicator');
    });

    it('is required', function (): void {
        $vocab = new ApplicatorVocabulary();

        expect($vocab->isRequired())->toBeTrue();
    });
})->covers(ApplicatorVocabulary::class);

describe('MetaDataVocabulary', function (): void {
    it('implements Vocabulary interface', function (): void {
        $vocab = new MetaDataVocabulary();

        expect($vocab)->toBeInstanceOf(Vocabulary::class);
    });

    it('has correct id', function (): void {
        $vocab = new MetaDataVocabulary();

        expect($vocab->id())->toBe('https://json-schema.org/draft/2020-12/vocab/meta-data');
    });

    it('is not required', function (): void {
        $vocab = new MetaDataVocabulary();

        expect($vocab->isRequired())->toBeFalse();
    });
})->covers(MetaDataVocabulary::class);

describe('FormatAnnotationVocabulary', function (): void {
    it('implements Vocabulary interface', function (): void {
        $vocab = new FormatAnnotationVocabulary();

        expect($vocab)->toBeInstanceOf(Vocabulary::class);
    });

    it('has correct id', function (): void {
        $vocab = new FormatAnnotationVocabulary();

        expect($vocab->id())->toBe('https://json-schema.org/draft/2020-12/vocab/format-annotation');
    });

    it('is not required', function (): void {
        $vocab = new FormatAnnotationVocabulary();

        expect($vocab->isRequired())->toBeFalse();
    });
})->covers(FormatAnnotationVocabulary::class);

describe('UnevaluatedVocabulary', function (): void {
    it('implements Vocabulary interface', function (): void {
        $vocab = new UnevaluatedVocabulary();

        expect($vocab)->toBeInstanceOf(Vocabulary::class);
    });

    it('has correct id', function (): void {
        $vocab = new UnevaluatedVocabulary();

        expect($vocab->id())->toBe('https://json-schema.org/draft/2020-12/vocab/unevaluated');
    });

    it('is not required', function (): void {
        $vocab = new UnevaluatedVocabulary();

        expect($vocab->isRequired())->toBeFalse();
    });
})->covers(UnevaluatedVocabulary::class);

describe('ContentVocabulary', function (): void {
    it('implements Vocabulary interface', function (): void {
        $vocab = new ContentVocabulary();

        expect($vocab)->toBeInstanceOf(Vocabulary::class);
    });

    it('has correct id', function (): void {
        $vocab = new ContentVocabulary();

        expect($vocab->id())->toBe('https://json-schema.org/draft/2020-12/vocab/content');
    });

    it('is not required', function (): void {
        $vocab = new ContentVocabulary();

        expect($vocab->isRequired())->toBeFalse();
    });

    it('returns array of keyword classes', function (): void {
        $vocab = new ContentVocabulary();
        $keywords = $vocab->keywords();

        expect($keywords)->toBeArray();
        expect($keywords)->toHaveCount(3);
    });
})->covers(ContentVocabulary::class);
