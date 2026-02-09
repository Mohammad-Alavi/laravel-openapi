<?php

declare(strict_types=1);

use MohammadAlavi\Laragen\RequestSchema\RuleToSchema;
use Tests\Laragen\Support\Doubles\SometimesFormRequest;

describe('sometimes rule', function (): void {
    it('keeps sometimes fields out of the required array', function (): void {
        $schema = RuleToSchema::transform(SometimesFormRequest::class)->compile();

        expect($schema)->toHaveKey('required')
            ->and($schema['required'])->toContain('name')
            ->and($schema['required'])->not->toContain('nickname')
            ->and($schema['required'])->not->toContain('bio');
    });

    it('still includes sometimes fields as properties', function (): void {
        $schema = RuleToSchema::transform(SometimesFormRequest::class)->compile();

        expect($schema)->toHaveKey('properties')
            ->and($schema['properties'])->toHaveKeys(['name', 'nickname', 'bio']);
    });
})->covers(RuleToSchema::class);
