<?php

use MohammadAlavi\LaravelRulesToSchema\RuleDocumentation;

describe(class_basename(RuleDocumentation::class), function (): void {
    it('creates with all fields', function (): void {
        $doc = new RuleDocumentation(
            type: 'string',
            format: 'date-time',
            description: 'A valid datetime',
            enum: ['a', 'b'],
        );

        expect($doc->type)->toBe('string')
            ->and($doc->format)->toBe('date-time')
            ->and($doc->description)->toBe('A valid datetime')
            ->and($doc->enum)->toBe(['a', 'b'])
            ->and($doc->hasType())->toBeTrue()
            ->and($doc->hasFormat())->toBeTrue()
            ->and($doc->hasDescription())->toBeTrue()
            ->and($doc->hasEnum())->toBeTrue();
    });

    it('creates with defaults', function (): void {
        $doc = new RuleDocumentation();

        expect($doc->type)->toBeNull()
            ->and($doc->format)->toBeNull()
            ->and($doc->description)->toBeNull()
            ->and($doc->enum)->toBe([])
            ->and($doc->hasType())->toBeFalse()
            ->and($doc->hasFormat())->toBeFalse()
            ->and($doc->hasDescription())->toBeFalse()
            ->and($doc->hasEnum())->toBeFalse();
    });

    it('creates with partial fields', function (): void {
        $doc = new RuleDocumentation(type: 'integer');

        expect($doc->hasType())->toBeTrue()
            ->and($doc->hasFormat())->toBeFalse()
            ->and($doc->hasDescription())->toBeFalse()
            ->and($doc->hasEnum())->toBeFalse();
    });
})->covers(RuleDocumentation::class);
