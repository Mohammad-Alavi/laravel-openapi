<?php

declare(strict_types=1);

use MohammadAlavi\LaravelRulesToSchema\Contracts\HasJsonSchema;
use MohammadAlavi\LaravelRulesToSchema\CustomRuleSchemaMapping;
use MohammadAlavi\LaravelRulesToSchema\NestedRuleset;
use MohammadAlavi\LaravelRulesToSchema\Parsers\CustomRuleSchemaParser;
use MohammadAlavi\LaravelRulesToSchema\ValidationRule;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Type;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\LooseFluentDescriptor;

describe(class_basename(CustomRuleSchemaParser::class), function (): void {
    it('applies schema from rule implementing HasJsonSchema', function (): void {
        $parser = new CustomRuleSchemaParser();
        $rule = new class implements HasJsonSchema {
            public function toJsonSchema(string $attribute): LooseFluentDescriptor
            {
                return LooseFluentDescriptor::withoutSchema()->type(Type::string())->description('from-rule');
            }
        };
        $schema = LooseFluentDescriptor::withoutSchema();

        $result = $parser('field', $schema, [new ValidationRule($rule)], new NestedRuleset());

        $compiled = $result->compile();
        expect($compiled['type'])->toBe('string')
            ->and($compiled['description'])->toBe('from-rule');
    });

    it('applies schema provider from config mapping', function (): void {
        $providerClass = 'Tests\StubCustomSchemaProvider_' . mt_rand();
        eval('namespace Tests; final class ' . substr($providerClass, 6) . " implements \MohammadAlavi\LaravelRulesToSchema\Contracts\HasJsonSchema { public function toJsonSchema(string \$attribute): \MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\LooseFluentDescriptor { return \MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\LooseFluentDescriptor::withoutSchema()->type(\MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Type::integer())->description('from-config'); } }");

        $parser = new CustomRuleSchemaParser([
            $providerClass => CustomRuleSchemaMapping::schemaProvider($providerClass),
        ]);
        $schema = LooseFluentDescriptor::withoutSchema();

        $result = $parser('field', $schema, [new ValidationRule($providerClass)], new NestedRuleset());

        $compiled = $result->compile();
        expect($compiled['type'])->toBe('integer')
            ->and($compiled['description'])->toBe('from-config');
    });

    it('applies single type from config mapping', function (): void {
        $parser = new CustomRuleSchemaParser([
            'custom_rule' => CustomRuleSchemaMapping::type('string'),
        ]);
        $schema = LooseFluentDescriptor::withoutSchema();

        $result = $parser('field', $schema, [new ValidationRule('custom_rule')], new NestedRuleset());

        expect($result->compile()['type'])->toBe('string');
    });

    it('applies multiple types from config mapping', function (): void {
        $parser = new CustomRuleSchemaParser([
            'custom_rule' => CustomRuleSchemaMapping::types(['null', 'string']),
        ]);
        $schema = LooseFluentDescriptor::withoutSchema();

        $result = $parser('field', $schema, [new ValidationRule('custom_rule')], new NestedRuleset());

        expect($result->compile()['type'])->toBe(['null', 'string']);
    });

    it('does not modify schema for unmatched rules', function (): void {
        $parser = new CustomRuleSchemaParser([
            'other_rule' => CustomRuleSchemaMapping::type('string'),
        ]);
        $schema = LooseFluentDescriptor::withoutSchema();

        $result = $parser('field', $schema, [new ValidationRule('required')], new NestedRuleset());

        expect($result->compile())->toBe([]);
    });

    it('returns schema unchanged with no custom schemas configured', function (): void {
        $parser = new CustomRuleSchemaParser();
        $schema = LooseFluentDescriptor::withoutSchema();

        $result = $parser('field', $schema, [new ValidationRule('required')], new NestedRuleset());

        expect($result->compile())->toBe([]);
    });

    it('prioritizes HasJsonSchema rule over config mapping', function (): void {
        $rule = new class implements HasJsonSchema {
            public function toJsonSchema(string $attribute): LooseFluentDescriptor
            {
                return LooseFluentDescriptor::withoutSchema()->type(Type::boolean());
            }
        };

        $parser = new CustomRuleSchemaParser([
            get_class($rule) => CustomRuleSchemaMapping::type('string'),
        ]);
        $schema = LooseFluentDescriptor::withoutSchema();

        $result = $parser('field', $schema, [new ValidationRule($rule)], new NestedRuleset());

        expect($result->compile()['type'])->toBe('boolean');
    });
})->covers(CustomRuleSchemaParser::class);
