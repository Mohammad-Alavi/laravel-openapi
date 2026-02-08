<?php

declare(strict_types=1);

use MohammadAlavi\Laragen\ArraySchema\ArrayField;
use MohammadAlavi\Laragen\ArraySchema\ArraySchemaAnalyzer;
use Tests\Laragen\Support\Doubles\Resources\PostResource;
use Tests\Laragen\Support\Doubles\Resources\ResourceWithCasts;
use Tests\Laragen\Support\Doubles\Resources\ResourceWithClassConstants;
use Tests\Laragen\Support\Doubles\Resources\ResourceWithCollection;
use Tests\Laragen\Support\Doubles\Resources\ResourceWithConditionals;
use Tests\Laragen\Support\Doubles\Resources\ResourceWithExpressions;
use Tests\Laragen\Support\Doubles\Resources\ResourceWithFunctionCalls;
use Tests\Laragen\Support\Doubles\Resources\ResourceWithLiterals;
use Tests\Laragen\Support\Doubles\Resources\ResourceWithMerge;
use Tests\Laragen\Support\Doubles\Resources\ResourceWithMethodChains;
use Tests\Laragen\Support\Doubles\Resources\ResourceWithNestedArray;
use Tests\Laragen\Support\Doubles\Resources\ResourceWithNullsafe;
use Tests\Laragen\Support\Doubles\Resources\ResourceWithTernary;
use Tests\Laragen\Support\Doubles\Resources\UserResource;

describe(class_basename(ArraySchemaAnalyzer::class), function (): void {
    it('analyzes any class method that returns an array', function (): void {
        $analyzer = new ArraySchemaAnalyzer();
        $fields = $analyzer->analyzeMethod(UserResource::class, 'toArray');

        $fieldNames = array_map(static fn (ArrayField $f): string => $f->name, $fields);

        expect($fieldNames)->toBe(['id', 'name', 'email', 'type', 'is_active']);
    });

    it('detects model property references', function (): void {
        $analyzer = new ArraySchemaAnalyzer();
        $fields = $analyzer->analyzeMethod(UserResource::class, 'toArray');

        $idField = $fields[0];

        expect($idField->isModelProperty)->toBeTrue()
            ->and($idField->modelProperty)->toBe('id');
    });

    it('detects all literal types including bool and null', function (): void {
        $analyzer = new ArraySchemaAnalyzer();
        $fields = $analyzer->analyzeMethod(ResourceWithLiterals::class, 'toArray');

        $verified = collect($fields)->firstWhere('name', 'is_verified');
        $banned = collect($fields)->firstWhere('name', 'is_banned');
        $deleted = collect($fields)->firstWhere('name', 'deleted_at');
        $type = collect($fields)->firstWhere('name', 'type');

        expect($verified->isLiteral)->toBeTrue()
            ->and($verified->literalValue)->toBeTrue()
            ->and($banned->isLiteral)->toBeTrue()
            ->and($banned->literalValue)->toBeFalse()
            ->and($deleted->isLiteral)->toBeTrue()
            ->and($deleted->literalValue)->toBeNull()
            ->and($type->isLiteral)->toBeTrue()
            ->and($type->literalValue)->toBe('user');
    });

    it('detects method chains on model properties', function (): void {
        $analyzer = new ArraySchemaAnalyzer();
        $fields = $analyzer->analyzeMethod(ResourceWithMethodChains::class, 'toArray');

        $created = collect($fields)->firstWhere('name', 'created_date');

        expect($created->isModelProperty)->toBeTrue()
            ->and($created->modelProperty)->toBe('created_at');
    });

    it('detects relationships via new Resource()', function (): void {
        $analyzer = new ArraySchemaAnalyzer();
        $fields = $analyzer->analyzeMethod(PostResource::class, 'toArray');

        $author = collect($fields)->firstWhere('name', 'author');

        expect($author->isRelationship)->toBeTrue()
            ->and($author->resourceClass)->toBe(UserResource::class);
    });

    it('detects static collection calls', function (): void {
        $analyzer = new ArraySchemaAnalyzer();
        $fields = $analyzer->analyzeMethod(ResourceWithCollection::class, 'toArray');

        $posts = collect($fields)->firstWhere('name', 'posts');

        expect($posts->isCollection)->toBeTrue()
            ->and($posts->resourceClass)->toBe(PostResource::class);
    });

    it('detects conditional methods', function (): void {
        $analyzer = new ArraySchemaAnalyzer();
        $fields = $analyzer->analyzeMethod(ResourceWithConditionals::class, 'toArray');

        $tags = collect($fields)->firstWhere('name', 'tags');

        expect($tags->isConditional)->toBeTrue();
    });

    it('flattens merge fields into parent', function (): void {
        $analyzer = new ArraySchemaAnalyzer();
        $fields = $analyzer->analyzeMethod(ResourceWithMerge::class, 'toArray');

        $fieldNames = array_map(static fn (ArrayField $f): string => $f->name, $fields);

        expect($fieldNames)->toContain('first_name', 'last_name', 'role', 'permissions', 'settings');
    });

    it('classifies unrecognized expressions as unknown', function (): void {
        $analyzer = new ArraySchemaAnalyzer();
        $fields = $analyzer->analyzeMethod(ResourceWithTernary::class, 'toArray');

        $status = collect($fields)->firstWhere('name', 'status');

        expect($status->isModelProperty)->toBeFalse()
            ->and($status->isLiteral)->toBeFalse()
            ->and($status->isConditional)->toBeFalse()
            ->and($status->isRelationship)->toBeFalse()
            ->and($status->isCollection)->toBeFalse();
    });

    it('classifies null coalescing left side as model property', function (): void {
        $analyzer = new ArraySchemaAnalyzer();
        $fields = $analyzer->analyzeMethod(ResourceWithTernary::class, 'toArray');

        $label = collect($fields)->firstWhere('name', 'label');

        expect($label->isModelProperty)->toBeTrue()
            ->and($label->modelProperty)->toBe('name');
    });

    it('classifies nullsafe property fetch as model property', function (): void {
        $analyzer = new ArraySchemaAnalyzer();
        $fields = $analyzer->analyzeMethod(ResourceWithNullsafe::class, 'toArray');

        $relationName = collect($fields)->firstWhere('name', 'relation_name');

        expect($relationName->isModelProperty)->toBeTrue()
            ->and($relationName->modelProperty)->toBe('relation');
    });

    it('classifies nullsafe method call as model property', function (): void {
        $analyzer = new ArraySchemaAnalyzer();
        $fields = $analyzer->analyzeMethod(ResourceWithNullsafe::class, 'toArray');

        $formattedDate = collect($fields)->firstWhere('name', 'formatted_date');

        expect($formattedDate->isModelProperty)->toBeTrue()
            ->and($formattedDate->modelProperty)->toBe('relation');
    });

    it('unwraps type casts and classifies inner expression', function (): void {
        $analyzer = new ArraySchemaAnalyzer();
        $fields = $analyzer->analyzeMethod(ResourceWithCasts::class, 'toArray');

        $count = collect($fields)->firstWhere('name', 'count');
        $label = collect($fields)->firstWhere('name', 'label');
        $price = collect($fields)->firstWhere('name', 'price');
        $active = collect($fields)->firstWhere('name', 'active');
        $tags = collect($fields)->firstWhere('name', 'tags');

        expect($count->isModelProperty)->toBeTrue()
            ->and($count->modelProperty)->toBe('count')
            ->and($label->isModelProperty)->toBeTrue()
            ->and($label->modelProperty)->toBe('label')
            ->and($price->isModelProperty)->toBeTrue()
            ->and($price->modelProperty)->toBe('price')
            ->and($active->isModelProperty)->toBeTrue()
            ->and($active->modelProperty)->toBe('active')
            ->and($tags->isModelProperty)->toBeTrue()
            ->and($tags->modelProperty)->toBe('tags');
    });

    it('classifies inline array literals as nested objects', function (): void {
        $analyzer = new ArraySchemaAnalyzer();
        $fields = $analyzer->analyzeMethod(ResourceWithNestedArray::class, 'toArray');

        $meta = collect($fields)->firstWhere('name', 'meta');

        expect($meta->isNestedObject)->toBeTrue()
            ->and($meta->children)->toHaveCount(2);

        $childNames = array_map(static fn (ArrayField $f): string => $f->name, $meta->children);

        expect($childNames)->toBe(['created_at', 'version']);
    });

    it('classifies function calls wrapping model properties', function (): void {
        $analyzer = new ArraySchemaAnalyzer();
        $fields = $analyzer->analyzeMethod(ResourceWithFunctionCalls::class, 'toArray');

        $upperName = collect($fields)->firstWhere('name', 'upper_name');
        $trimmed = collect($fields)->firstWhere('name', 'trimmed');
        $computed = collect($fields)->firstWhere('name', 'computed');

        expect($upperName->isModelProperty)->toBeTrue()
            ->and($upperName->modelProperty)->toBe('name')
            ->and($trimmed->isModelProperty)->toBeTrue()
            ->and($trimmed->modelProperty)->toBe('bio')
            ->and($computed->isModelProperty)->toBeFalse()
            ->and($computed->isLiteral)->toBeFalse();
    });

    it('resolves class constants to literal values', function (): void {
        $analyzer = new ArraySchemaAnalyzer();
        $fields = $analyzer->analyzeMethod(ResourceWithClassConstants::class, 'toArray');

        $type = collect($fields)->firstWhere('name', 'type');
        $unresolvable = collect($fields)->firstWhere('name', 'unresolvable');

        expect($type->isLiteral)->toBeTrue()
            ->and($type->literalValue)->toBe('admin')
            ->and($unresolvable->isLiteral)->toBeFalse();
    });

    it('classifies string concatenation as typed expression', function (): void {
        $analyzer = new ArraySchemaAnalyzer();
        $fields = $analyzer->analyzeMethod(ResourceWithExpressions::class, 'toArray');

        $fullName = collect($fields)->firstWhere('name', 'full_name');

        expect($fullName->isTypedExpression)->toBeTrue()
            ->and($fullName->expressionType)->toBe('string');
    });

    it('classifies arithmetic operations as typed expression', function (): void {
        $analyzer = new ArraySchemaAnalyzer();
        $fields = $analyzer->analyzeMethod(ResourceWithExpressions::class, 'toArray');

        $totalCents = collect($fields)->firstWhere('name', 'total_cents');
        $half = collect($fields)->firstWhere('name', 'half');
        $sum = collect($fields)->firstWhere('name', 'sum');
        $diff = collect($fields)->firstWhere('name', 'diff');
        $remainder = collect($fields)->firstWhere('name', 'remainder');

        expect($totalCents->isTypedExpression)->toBeTrue()
            ->and($totalCents->expressionType)->toBe('number')
            ->and($half->isTypedExpression)->toBeTrue()
            ->and($half->expressionType)->toBe('number')
            ->and($sum->isTypedExpression)->toBeTrue()
            ->and($sum->expressionType)->toBe('number')
            ->and($diff->isTypedExpression)->toBeTrue()
            ->and($diff->expressionType)->toBe('number')
            ->and($remainder->isTypedExpression)->toBeTrue()
            ->and($remainder->expressionType)->toBe('number');
    });

    it('classifies boolean NOT as typed expression', function (): void {
        $analyzer = new ArraySchemaAnalyzer();
        $fields = $analyzer->analyzeMethod(ResourceWithExpressions::class, 'toArray');

        $isInactive = collect($fields)->firstWhere('name', 'is_inactive');

        expect($isInactive->isTypedExpression)->toBeTrue()
            ->and($isInactive->expressionType)->toBe('boolean');
    });

    it('classifies comparison operations as typed expression', function (): void {
        $analyzer = new ArraySchemaAnalyzer();
        $fields = $analyzer->analyzeMethod(ResourceWithExpressions::class, 'toArray');

        $isAdult = collect($fields)->firstWhere('name', 'is_adult');
        $isSame = collect($fields)->firstWhere('name', 'is_same');

        expect($isAdult->isTypedExpression)->toBeTrue()
            ->and($isAdult->expressionType)->toBe('boolean')
            ->and($isSame->isTypedExpression)->toBeTrue()
            ->and($isSame->expressionType)->toBe('boolean');
    });

    it('classifies match expression as typed expression', function (): void {
        $analyzer = new ArraySchemaAnalyzer();
        $fields = $analyzer->analyzeMethod(ResourceWithExpressions::class, 'toArray');

        $statusLabel = collect($fields)->firstWhere('name', 'status_label');

        expect($statusLabel->isTypedExpression)->toBeTrue()
            ->and($statusLabel->expressionType)->toBe('string');
    });
})->covers(ArraySchemaAnalyzer::class);
