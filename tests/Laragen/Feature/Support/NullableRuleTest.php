<?php

declare(strict_types=1);

use MohammadAlavi\Laragen\Support\RuleToSchema;
use Tests\Laragen\Support\Doubles\BodyFormRequest;

describe('nullable rule', function (): void {
    it('adds null type for nullable fields', function (): void {
        $schema = RuleToSchema::transform(BodyFormRequest::class)->compile();

        $ageSchema = $schema['properties']['age'] ?? [];

        // nullable adds 'null' type via type array
        $types = (array) ($ageSchema['type'] ?? []);

        expect($types)->toContain('null');
    });
})->covers(RuleToSchema::class);
