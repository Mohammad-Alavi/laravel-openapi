<?php

use MohammadAlavi\LaravelRulesToSchema\NestedRuleset;
use MohammadAlavi\LaravelRulesToSchema\Parsers\RequiredWithoutParser;
use MohammadAlavi\LaravelRulesToSchema\ValidationRule;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Properties\Property;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Type;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\LooseFluentDescriptor;

describe(class_basename(RequiredWithoutParser::class), function (): void {
    it('adds if/then conditions for mutual required_without', function (): void {
        $parser = new RequiredWithoutParser();
        $baseSchema = LooseFluentDescriptor::withoutSchema()
            ->type(Type::object())
            ->properties(
                Property::create('name', LooseFluentDescriptor::withoutSchema()->type(Type::string())),
                Property::create('email', LooseFluentDescriptor::withoutSchema()->type(Type::string())),
            );

        $allRules = [
            'name' => new NestedRuleset([new ValidationRule('string'), new ValidationRule('required_without', ['email'])]),
            'email' => new NestedRuleset([new ValidationRule('string'), new ValidationRule('required_without', ['name'])]),
        ];

        $contextual = $parser->withContext($baseSchema, $allRules, null);
        $nameSchema = LooseFluentDescriptor::withoutSchema()->type(Type::string());
        $emailSchema = LooseFluentDescriptor::withoutSchema()->type(Type::string());

        // Invoke for each attribute (parser batches on last attribute)
        $contextual('name', $nameSchema, [new ValidationRule('string'), new ValidationRule('required_without', ['email'])], new NestedRuleset());
        $contextual('email', $emailSchema, [new ValidationRule('string'), new ValidationRule('required_without', ['name'])], new NestedRuleset());

        $modified = $contextual->modifiedBaseSchema();
        expect($modified)->not->toBeNull();

        $compiled = $modified->compile();

        expect($compiled)->toHaveKey('properties')
            ->and($compiled['properties'])->toHaveKey('name')
            ->and($compiled['properties'])->toHaveKey('email')
            ->and($compiled)->toHaveKey('allOf')
            ->and($compiled['allOf'])->toHaveCount(2);

        // First condition: if email is absent, name is required
        $firstCondition = $compiled['allOf'][0];
        expect($firstCondition)->toHaveKey('if')
            ->and($firstCondition['if'])->toHaveKey('not')
            ->and($firstCondition['if']['not'])->toBe(['properties' => ['email' => []], 'required' => ['email']])
            ->and($firstCondition['then'])->toBe(['properties' => ['name' => []], 'required' => ['name']]);

        // Second condition: if name is absent, email is required
        $secondCondition = $compiled['allOf'][1];
        expect($secondCondition)->toHaveKey('if')
            ->and($secondCondition['if'])->toHaveKey('not')
            ->and($secondCondition['if']['not'])->toBe(['properties' => ['name' => []], 'required' => ['name']])
            ->and($secondCondition['then'])->toBe(['properties' => ['email' => []], 'required' => ['email']]);
    });

    it('handles required_without with multiple arguments', function (): void {
        $parser = new RequiredWithoutParser();
        $baseSchema = LooseFluentDescriptor::withoutSchema()
            ->type(Type::object())
            ->properties(
                Property::create('phone', LooseFluentDescriptor::withoutSchema()->type(Type::string())),
                Property::create('email', LooseFluentDescriptor::withoutSchema()->type(Type::string())),
                Property::create('fax', LooseFluentDescriptor::withoutSchema()->type(Type::string())),
            );

        $allRules = [
            'phone' => new NestedRuleset([new ValidationRule('string'), new ValidationRule('required_without', ['email', 'fax'])]),
            'email' => new NestedRuleset([new ValidationRule('string')]),
            'fax' => new NestedRuleset([new ValidationRule('string')]),
        ];

        $contextual = $parser->withContext($baseSchema, $allRules, null);

        $contextual('phone', LooseFluentDescriptor::withoutSchema()->type(Type::string()), [new ValidationRule('string'), new ValidationRule('required_without', ['email', 'fax'])], new NestedRuleset());
        $contextual('email', LooseFluentDescriptor::withoutSchema()->type(Type::string()), [new ValidationRule('string')], new NestedRuleset());
        $contextual('fax', LooseFluentDescriptor::withoutSchema()->type(Type::string()), [new ValidationRule('string')], new NestedRuleset());

        $modified = $contextual->modifiedBaseSchema();
        expect($modified)->not->toBeNull();

        $compiled = $modified->compile();

        expect($compiled)->toHaveKey('properties')
            ->and($compiled['properties'])->toHaveKey('phone')
            ->and($compiled['properties'])->toHaveKey('email')
            ->and($compiled['properties'])->toHaveKey('fax')
            ->and($compiled)->toHaveKey('allOf')
            ->and($compiled['allOf'])->toHaveCount(1);

        $condition = $compiled['allOf'][0];
        expect($condition['if']['not'])->toBe(['properties' => ['email' => [], 'fax' => []], 'required' => ['email', 'fax']])
            ->and($condition['then'])->toBe(['properties' => ['phone' => []], 'required' => ['phone']]);
    });

    it('preserves properties for mixed fields with and without required_without', function (): void {
        $parser = new RequiredWithoutParser();
        $baseSchema = LooseFluentDescriptor::withoutSchema()
            ->type(Type::object())
            ->properties(
                Property::create('name', LooseFluentDescriptor::withoutSchema()->type(Type::string())),
                Property::create('email', LooseFluentDescriptor::withoutSchema()->type(Type::string())),
                Property::create('age', LooseFluentDescriptor::withoutSchema()->type(Type::integer())),
            );

        $allRules = [
            'name' => new NestedRuleset([new ValidationRule('string'), new ValidationRule('required_without', ['email'])]),
            'email' => new NestedRuleset([new ValidationRule('string'), new ValidationRule('required_without', ['name'])]),
            'age' => new NestedRuleset([new ValidationRule('integer')]),
        ];

        $contextual = $parser->withContext($baseSchema, $allRules, null);

        $contextual('name', LooseFluentDescriptor::withoutSchema()->type(Type::string()), [new ValidationRule('string'), new ValidationRule('required_without', ['email'])], new NestedRuleset());
        $contextual('email', LooseFluentDescriptor::withoutSchema()->type(Type::string()), [new ValidationRule('string'), new ValidationRule('required_without', ['name'])], new NestedRuleset());
        $contextual('age', LooseFluentDescriptor::withoutSchema()->type(Type::integer()), [new ValidationRule('integer')], new NestedRuleset());

        $modified = $contextual->modifiedBaseSchema();
        expect($modified)->not->toBeNull();

        $compiled = $modified->compile();

        expect($compiled)->toHaveKey('properties')
            ->and($compiled['properties'])->toHaveKey('name')
            ->and($compiled['properties'])->toHaveKey('email')
            ->and($compiled['properties'])->toHaveKey('age')
            ->and($compiled)->toHaveKey('allOf')
            ->and($compiled['allOf'])->toHaveCount(2);
    });

    it('does not modify schema when no required_without rules exist', function (): void {
        $parser = new RequiredWithoutParser();
        $baseSchema = LooseFluentDescriptor::withoutSchema()
            ->type(Type::object())
            ->properties(
                Property::create('name', LooseFluentDescriptor::withoutSchema()->type(Type::string())),
            );

        $allRules = [
            'name' => new NestedRuleset([new ValidationRule('string'), new ValidationRule('required')]),
        ];

        $contextual = $parser->withContext($baseSchema, $allRules, null);

        $contextual('name', LooseFluentDescriptor::withoutSchema()->type(Type::string()), [new ValidationRule('string'), new ValidationRule('required')], new NestedRuleset());

        $modified = $contextual->modifiedBaseSchema();
        expect($modified)->toBeNull();
    });

    it('preserves nullable type when required_without fields coexist', function (): void {
        $parser = new RequiredWithoutParser();
        $baseSchema = LooseFluentDescriptor::withoutSchema()
            ->type(Type::object())
            ->properties(
                Property::create('name', LooseFluentDescriptor::withoutSchema()->type(Type::string())),
                Property::create('email', LooseFluentDescriptor::withoutSchema()->type(Type::string())),
                Property::create('age', LooseFluentDescriptor::withoutSchema()->type(Type::integer(), Type::null())),
            );

        $allRules = [
            'name' => new NestedRuleset([new ValidationRule('string'), new ValidationRule('required_without', ['email'])]),
            'email' => new NestedRuleset([new ValidationRule('string'), new ValidationRule('required_without', ['name'])]),
            'age' => new NestedRuleset([new ValidationRule('nullable'), new ValidationRule('integer')]),
        ];

        $contextual = $parser->withContext($baseSchema, $allRules, null);

        $contextual('name', LooseFluentDescriptor::withoutSchema()->type(Type::string()), [new ValidationRule('string'), new ValidationRule('required_without', ['email'])], new NestedRuleset());
        $contextual('email', LooseFluentDescriptor::withoutSchema()->type(Type::string()), [new ValidationRule('string'), new ValidationRule('required_without', ['name'])], new NestedRuleset());
        $contextual('age', LooseFluentDescriptor::withoutSchema()->type(Type::integer(), Type::null()), [new ValidationRule('nullable'), new ValidationRule('integer')], new NestedRuleset());

        $modified = $contextual->modifiedBaseSchema();
        expect($modified)->not->toBeNull();

        $compiled = $modified->compile();

        expect($compiled)->toHaveKey('properties')
            ->and($compiled['properties'])->toHaveKey('age')
            ->and($compiled['properties']['age'])->toHaveKey('type')
            ->and((array) $compiled['properties']['age']['type'])->toContain('null');
    });

    it('returns schema unchanged without context', function (): void {
        $parser = new RequiredWithoutParser();
        $schema = LooseFluentDescriptor::withoutSchema();

        $result = $parser('field', $schema, [new ValidationRule('required_without', ['other'])], new NestedRuleset());

        expect($result->schema())->toBe($schema);
    });
})->covers(RequiredWithoutParser::class);
