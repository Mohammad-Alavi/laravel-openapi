<?php

declare(strict_types=1);

use MohammadAlavi\Laragen\Support\RuleToSchema;
use Tests\Laragen\Support\Doubles\NestedRulesFormRequest;

describe('nested rules', function (): void {
    it('produces nested objects for dot-notation rules', function (): void {
        $schema = RuleToSchema::transform(NestedRulesFormRequest::class)->compile();

        expect($schema)->toHaveKey('properties')
            ->and($schema['properties'])->toHaveKey('user');

        $userSchema = $schema['properties']['user'];

        expect($userSchema['type'])->toBe('object')
            ->and($userSchema)->toHaveKey('properties')
            ->and($userSchema['properties'])->toHaveKeys(['name', 'email']);
    });

    it('produces array items for wildcard rules', function (): void {
        $schema = RuleToSchema::transform(NestedRulesFormRequest::class)->compile();

        expect($schema['properties'])->toHaveKey('tags');

        $tagsSchema = $schema['properties']['tags'];

        expect($tagsSchema['type'])->toBe('array')
            ->and($tagsSchema)->toHaveKey('items')
            ->and($tagsSchema['items']['type'])->toBe('string');
    });
})->covers(RuleToSchema::class);
