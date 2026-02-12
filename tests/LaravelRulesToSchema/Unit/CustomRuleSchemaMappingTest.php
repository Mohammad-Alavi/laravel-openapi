<?php

use MohammadAlavi\LaravelRulesToSchema\Contracts\HasJsonSchema;
use MohammadAlavi\LaravelRulesToSchema\CustomRuleSchemaMapping;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Type;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\LooseFluentDescriptor;

describe(class_basename(CustomRuleSchemaMapping::class), function (): void {
    it('creates schema provider mapping', function (): void {
        $mapping = CustomRuleSchemaMapping::schemaProvider(StubSchemaProvider::class);

        expect($mapping->isSchemaProvider())->toBeTrue();
    });

    it('creates single type mapping', function (): void {
        $mapping = CustomRuleSchemaMapping::type('string');

        expect($mapping->isSchemaProvider())->toBeFalse();
    });

    it('creates multiple types mapping', function (): void {
        $mapping = CustomRuleSchemaMapping::types(['string', 'null']);

        expect($mapping->isSchemaProvider())->toBeFalse();
    });

    it('creates from class-string via from()', function (): void {
        $mapping = CustomRuleSchemaMapping::from(StubSchemaProvider::class);

        expect($mapping->isSchemaProvider())->toBeTrue();
    });

    it('creates from single type string via from()', function (): void {
        $mapping = CustomRuleSchemaMapping::from('string');

        expect($mapping->isSchemaProvider())->toBeFalse();
    });

    it('creates from type array via from()', function (): void {
        $mapping = CustomRuleSchemaMapping::from(['string', 'null']);

        expect($mapping->isSchemaProvider())->toBeFalse();
    });

    it('applies schema provider mapping', function (): void {
        $mapping = CustomRuleSchemaMapping::schemaProvider(StubSchemaProvider::class);
        app()->bind(StubSchemaProvider::class, fn () => new StubSchemaProvider());
        $schema = LooseFluentDescriptor::withoutSchema();

        $result = $mapping->apply('field', $schema);

        $compiled = $result->compile();
        expect($compiled['type'])->toBe('string')
            ->and($compiled['description'])->toBe('stub');
    });

    it('applies single type mapping', function (): void {
        $mapping = CustomRuleSchemaMapping::type('string');
        $schema = LooseFluentDescriptor::withoutSchema();

        $result = $mapping->apply('field', $schema);

        expect($result->compile()['type'])->toBe('string');
    });

    it('applies multiple types mapping', function (): void {
        $mapping = CustomRuleSchemaMapping::types(['string', 'null']);
        $schema = LooseFluentDescriptor::withoutSchema();

        $result = $mapping->apply('field', $schema);

        $compiled = $result->compile();
        expect($compiled['type'])->toBe(['string', 'null']);
    });

    it('throws for invalid value in from()', function (): void {
        CustomRuleSchemaMapping::from(42);
    })->throws(InvalidArgumentException::class);

    it('throws when schema provider does not implement HasJsonSchema', function (): void {
        $mapping = CustomRuleSchemaMapping::schemaProvider(StubNonSchemaProvider::class);
        app()->bind(StubNonSchemaProvider::class, fn () => new StubNonSchemaProvider());
        $schema = LooseFluentDescriptor::withoutSchema();

        $mapping->apply('field', $schema);
    })->throws(RuntimeException::class);
})->covers(CustomRuleSchemaMapping::class);

final class StubSchemaProvider implements HasJsonSchema
{
    public function toJsonSchema(string $attribute): LooseFluentDescriptor
    {
        return LooseFluentDescriptor::withoutSchema()->type(Type::string())->description('stub');
    }
}

final class StubNonSchemaProvider
{
}
