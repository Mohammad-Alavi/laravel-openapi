<?php

declare(strict_types=1);

use MohammadAlavi\Laragen\Annotations\DetectedBodyParam;
use MohammadAlavi\Laragen\Annotations\DetectedQueryParam;
use MohammadAlavi\Laragen\Annotations\DetectedResponseAnnotation;
use MohammadAlavi\Laragen\Annotations\DocBlockTagParser;
use Tests\Laragen\Support\Doubles\Annotations\AnnotatedController;

describe(class_basename(DocBlockTagParser::class), function (): void {
    describe('extractResponseTags', function (): void {
        it('extracts @response tag with status and JSON', function (): void {
            $tags = DocBlockTagParser::extractResponseTags(AnnotatedController::class, 'withResponse');

            expect($tags)->toHaveCount(1)
                ->and($tags[0])->toBeInstanceOf(DetectedResponseAnnotation::class)
                ->and($tags[0]->status)->toBe(200)
                ->and($tags[0]->json)->toBe('{"id": 1, "name": "John", "is_active": true}');
        });

        it('extracts @response tag without status defaulting to 200', function (): void {
            $tags = DocBlockTagParser::extractResponseTags(AnnotatedController::class, 'withResponseNoStatus');

            expect($tags)->toHaveCount(1)
                ->and($tags[0]->status)->toBe(200)
                ->and($tags[0]->json)->toBe('{"id": 1, "name": "John"}');
        });

        it('extracts multiple @response tags', function (): void {
            $tags = DocBlockTagParser::extractResponseTags(AnnotatedController::class, 'withMultipleResponses');

            expect($tags)->toHaveCount(2)
                ->and($tags[0]->status)->toBe(200)
                ->and($tags[0]->json)->toBe('{"id": 1, "name": "John"}')
                ->and($tags[1]->status)->toBe(404)
                ->and($tags[1]->json)->toBe('{"error": "Not found"}');
        });

        it('returns empty array when no @response tags', function (): void {
            $tags = DocBlockTagParser::extractResponseTags(AnnotatedController::class, 'withoutAnnotations');

            expect($tags)->toBe([]);
        });

        it('returns empty array for regular docblock', function (): void {
            $tags = DocBlockTagParser::extractResponseTags(AnnotatedController::class, 'withRegularDocblock');

            expect($tags)->toBe([]);
        });

        it('returns empty array for non-existent method', function (): void {
            $tags = DocBlockTagParser::extractResponseTags(AnnotatedController::class, 'nonExistent');

            expect($tags)->toBe([]);
        });
    });

    describe('extractBodyParamTags', function (): void {
        it('extracts @bodyParam tags', function (): void {
            $tags = DocBlockTagParser::extractBodyParamTags(AnnotatedController::class, 'withBodyParams');

            expect($tags)->toHaveCount(3)
                ->and($tags[0])->toBeInstanceOf(DetectedBodyParam::class)
                ->and($tags[0]->name)->toBe('name')
                ->and($tags[0]->type)->toBe('string')
                ->and($tags[0]->required)->toBeTrue()
                ->and($tags[0]->description)->toBe("The user's name")
                ->and($tags[1]->name)->toBe('age')
                ->and($tags[1]->type)->toBe('integer')
                ->and($tags[1]->required)->toBeFalse()
                ->and($tags[1]->description)->toBeNull()
                ->and($tags[2]->name)->toBe('is_active')
                ->and($tags[2]->type)->toBe('boolean')
                ->and($tags[2]->required)->toBeTrue()
                ->and($tags[2]->description)->toBeNull();
        });

        it('returns empty array when no @bodyParam tags', function (): void {
            $tags = DocBlockTagParser::extractBodyParamTags(AnnotatedController::class, 'withoutAnnotations');

            expect($tags)->toBe([]);
        });

        it('returns empty array for non-existent method', function (): void {
            $tags = DocBlockTagParser::extractBodyParamTags(AnnotatedController::class, 'nonExistent');

            expect($tags)->toBe([]);
        });
    });

    describe('extractQueryParamTags', function (): void {
        it('extracts @queryParam tags', function (): void {
            $tags = DocBlockTagParser::extractQueryParamTags(AnnotatedController::class, 'withQueryParams');

            expect($tags)->toHaveCount(3)
                ->and($tags[0])->toBeInstanceOf(DetectedQueryParam::class)
                ->and($tags[0]->name)->toBe('page')
                ->and($tags[0]->type)->toBe('integer')
                ->and($tags[0]->description)->toBe('The page number')
                ->and($tags[1]->name)->toBe('per_page')
                ->and($tags[1]->type)->toBe('integer')
                ->and($tags[1]->description)->toBeNull()
                ->and($tags[2]->name)->toBe('search')
                ->and($tags[2]->type)->toBe('string')
                ->and($tags[2]->description)->toBe('The search term');
        });

        it('returns empty array when no @queryParam tags', function (): void {
            $tags = DocBlockTagParser::extractQueryParamTags(AnnotatedController::class, 'withoutAnnotations');

            expect($tags)->toBe([]);
        });

        it('returns empty array for non-existent method', function (): void {
            $tags = DocBlockTagParser::extractQueryParamTags(AnnotatedController::class, 'nonExistent');

            expect($tags)->toBe([]);
        });
    });

    describe('mixed annotations', function (): void {
        it('extracts only the requested tag type from mixed annotations', function (): void {
            $responses = DocBlockTagParser::extractResponseTags(AnnotatedController::class, 'withMixedAnnotations');
            $bodyParams = DocBlockTagParser::extractBodyParamTags(AnnotatedController::class, 'withMixedAnnotations');
            $queryParams = DocBlockTagParser::extractQueryParamTags(AnnotatedController::class, 'withMixedAnnotations');

            expect($responses)->toHaveCount(1)
                ->and($bodyParams)->toHaveCount(1)
                ->and($queryParams)->toHaveCount(1);
        });
    });
})->covers(DocBlockTagParser::class);
