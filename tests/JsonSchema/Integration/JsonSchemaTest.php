<?php

use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Keyword;
use MohammadAlavi\ObjectOrientedJSONSchema\Trash\AvailableVocabulary;
use MohammadAlavi\ObjectOrientedJSONSchema\Trash\JSONSchema\Narrowers\Draft202012Constrained;
use MohammadAlavi\ObjectOrientedJSONSchema\Trash\MetaSchema\MetaSchema;
use MohammadAlavi\ObjectOrientedJSONSchema\v31\Schema;
use MohammadAlavi\ObjectOrientedJSONSchema\Vocabulary;

describe(class_basename(Schema::class), function (): void {
    it(
        'can create a keyword',
        function (): void {
            $keyword = new class implements Keyword {
                public static function name(): string
                {
                    return 'keyword';
                }

                public function value(): mixed
                {
                    return 'value';
                }
            };

            expect($keyword::name())->toBe('keyword');
        },
    );

    dataset('keywords', [
        [
            fn (): Keyword => new class implements Keyword {
                public static function name(): string
                {
                    return 'keywordA';
                }

                public function value(): string
                {
                    return 'valueA';
                }
            },
            fn (): Keyword => new class implements Keyword {
                public static function name(): string
                {
                    return 'keywordB';
                }

                public function value(): array
                {
                    return ['x' => 'y'];
                }
            },
            fn (): Keyword => new class implements Keyword {
                public static function name(): string
                {
                    return 'keywordC';
                }

                public function value(): int
                {
                    return 10;
                }
            },
        ],
    ]);

    it(
        'can compose Keywords to create a Vocabulary',
        function (Keyword $keywordA, Keyword $keywordB, Keyword $keywordC): void {
            $vocabulary = new Vocabulary('a-uri', $keywordA, $keywordC, $keywordB);

            expect($vocabulary->id())->toBe('a-uri')
                ->and($vocabulary->keywords())->toBe([$keywordA, $keywordC, $keywordB]);
        },
    )->with('keywords');

    it(
        'can compose Vocabularies to create a MetaSchema',
        function (Keyword $keywordA, Keyword $keywordB, Keyword $keywordC): void {
            $vocabA = new Vocabulary('vocab-a', $keywordA);
            $vocabB = new Vocabulary('vocab-b', $keywordB, $keywordC);
            $vocabsA = new AvailableVocabulary($vocabA, true);
            $vocabsB = new AvailableVocabulary($vocabB, false);
            $metaSchema = new MetaSchema('meta-schema-id', 'meta-schema-schema', $vocabsA, $vocabsB);

            expect($metaSchema->id())->toBe('meta-schema-id')
                ->and($metaSchema->schema())->toBe('meta-schema-schema')
                ->and($metaSchema->availableVocabularies())->toBe([$vocabsA, $vocabsB])
                ->and($metaSchema->availableVocabularies()[0]->id())->toBe('vocab-a')
                ->and($metaSchema->availableVocabularies()[1]->id())->toBe('vocab-b')
                ->and($metaSchema->availableVocabularies()[0]->required())->toBeTrue()
                ->and($metaSchema->availableVocabularies()[1]->required())->toBeFalse()
                ->and($metaSchema->availableKeywords())->toEqual(
                    [
                        ...$vocabA->keywords(),
                        ...$vocabB->keywords(),
                    ],
                );
        },
    )->with('keywords');

    it(
        'wont allow duplicate keyword',
        function (Keyword $keywordA, Keyword $keywordB, Keyword $keywordC): void {
            $vocabA = new Vocabulary('vocab-a', $keywordA, $keywordB, $keywordC);
            $vocabB = new Vocabulary('vocab-b', $keywordC, $keywordA);
            $vocabsA = new AvailableVocabulary($vocabA, false);
            $vocabsB = new AvailableVocabulary($vocabB, true);

            expect(function () use ($vocabsA, $vocabsB): void {
                new MetaSchema('meta-schema-id', 'meta-schema-schema', $vocabsA, $vocabsB);
            })->toThrow(
                DomainException::class,
                sprintf('Duplicate keywords found: %s, %s', $keywordC->name(), $keywordA->name()),
            );
        },
    )->with('keywords');

    it(
        'can compose MetaSchemas to create a another MetaSchema',
        function (): void {
        },
    )->todo();

    it(
        'can create a Dialect',
        function (Keyword $keywordA, Keyword $keywordB, Keyword $keywordC): void {
            $vocabA = new Vocabulary('vocab-a', $keywordA);
            $vocabB = new Vocabulary('vocab-b', $keywordB, $keywordC);
            $vocabsA = new AvailableVocabulary($vocabA, true);
            $vocabsB = new AvailableVocabulary($vocabB, false);
            $metaSchema = new MetaSchema('meta-schema-id', 'meta-schema-schema', $vocabsA, $vocabsB);
            $dialect = new class($metaSchema) implements Draft202012Constrained {
                public function __construct(private readonly MetaSchema $metaSchema)
                {
                }

                public function id(): string
                {
                    return 'OAS-3.1';
                }

                public function metaSchema(): MetaSchema
                {
                    return $this->metaSchema;
                }
            };

            expect($dialect->id())->toBe('OAS-3.1')
                ->and($dialect->metaSchema())->toBe($metaSchema);
        },
    )->with('keywords');

    it(
        'can create a Schema based on a Dialect',
        function (Keyword $keywordA, Keyword $keywordB, Keyword $keywordC): void {
            $vocabA = new Vocabulary('vocab-a', $keywordA);
            $vocabB = new Vocabulary('vocab-b', $keywordB, $keywordC);
            $vocabsA = new AvailableVocabulary($vocabA, true);
            $vocabsB = new AvailableVocabulary($vocabB, false);
            $metaSchema = new MetaSchema('meta-schema-id', 'meta-schema-schema', $vocabsA, $vocabsB);
            $dialect = new class($metaSchema) implements Draft202012Constrained {
                public function __construct(private readonly MetaSchema $metaSchema)
                {
                }

                public function id(): string
                {
                    return 'draft202012';
                }

                public function metaSchema(): MetaSchema
                {
                    return $this->metaSchema;
                }
            };

            // dd($dialect);
            // $draft202012 = JsonSchema::Draft202012();
            // $dialectOAS30 = OAS::Version30();
            // $dialectOAS31 = OAS::Version31();
        },
    )->with('keywords')->todo();
})->covers(Schema::class)->skip();
