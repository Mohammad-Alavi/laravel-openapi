<?php

declare(strict_types=1);

use MohammadAlavi\LaravelRulesToSchema\Parsers\RequiredWithParser;
use MohammadAlavi\LaravelRulesToSchema\ValidationRule;
use MohammadAlavi\LaravelRulesToSchema\ValidationRuleNormalizer;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Properties\Property;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Type;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\LooseFluentDescriptor;

describe(class_basename(RequiredWithParser::class), function (): void {
    it('adds if/then conditions for mutual required_with', function (): void {
        $parser = new RequiredWithParser();
        $baseSchema = LooseFluentDescriptor::withoutSchema()
            ->type(Type::object())
            ->properties(
                Property::create('name', LooseFluentDescriptor::withoutSchema()->type(Type::string())),
                Property::create('email', LooseFluentDescriptor::withoutSchema()->type(Type::string())),
            );

        $allRules = [
            'name' => [ValidationRuleNormalizer::RULES_KEY => [new ValidationRule('string'), new ValidationRule('required_with', ['email'])]],
            'email' => [ValidationRuleNormalizer::RULES_KEY => [new ValidationRule('string'), new ValidationRule('required_with', ['name'])]],
        ];

        $contextual = $parser->withContext($baseSchema, $allRules, null);
        $nameSchema = LooseFluentDescriptor::withoutSchema()->type(Type::string());
        $emailSchema = LooseFluentDescriptor::withoutSchema()->type(Type::string());

        $contextual('name', $nameSchema, [new ValidationRule('string'), new ValidationRule('required_with', ['email'])], []);
        $contextual('email', $emailSchema, [new ValidationRule('string'), new ValidationRule('required_with', ['name'])], []);

        $modified = $contextual->modifiedBaseSchema();
        expect($modified)->not->toBeNull();

        $compiled = $modified->compile();

        expect($compiled)->toHaveKey('properties')
            ->and($compiled['properties'])->toHaveKey('name')
            ->and($compiled['properties'])->toHaveKey('email')
            ->and($compiled)->toHaveKey('allOf')
            ->and($compiled['allOf'])->toHaveCount(2);

        // First condition: if email is present, name is required
        $firstCondition = $compiled['allOf'][0];
        expect($firstCondition)->toHaveKey('if')
            ->and($firstCondition['if'])->toBe(['required' => ['email']])
            ->and($firstCondition['then'])->toBe(['required' => ['name']]);

        // Second condition: if name is present, email is required
        $secondCondition = $compiled['allOf'][1];
        expect($secondCondition)->toHaveKey('if')
            ->and($secondCondition['if'])->toBe(['required' => ['name']])
            ->and($secondCondition['then'])->toBe(['required' => ['email']]);
    });

    it('handles required_with with multiple arguments using anyOf in if', function (): void {
        $parser = new RequiredWithParser();
        $baseSchema = LooseFluentDescriptor::withoutSchema()
            ->type(Type::object())
            ->properties(
                Property::create('name', LooseFluentDescriptor::withoutSchema()->type(Type::string())),
                Property::create('email', LooseFluentDescriptor::withoutSchema()->type(Type::string())),
                Property::create('age', LooseFluentDescriptor::withoutSchema()->type(Type::integer())),
            );

        $allRules = [
            'name' => [ValidationRuleNormalizer::RULES_KEY => [new ValidationRule('string')]],
            'email' => [ValidationRuleNormalizer::RULES_KEY => [new ValidationRule('string')]],
            'age' => [ValidationRuleNormalizer::RULES_KEY => [new ValidationRule('integer'), new ValidationRule('required_with', ['name', 'email'])]],
        ];

        $contextual = $parser->withContext($baseSchema, $allRules, null);

        $contextual('name', LooseFluentDescriptor::withoutSchema()->type(Type::string()), [new ValidationRule('string')], []);
        $contextual('email', LooseFluentDescriptor::withoutSchema()->type(Type::string()), [new ValidationRule('string')], []);
        $contextual('age', LooseFluentDescriptor::withoutSchema()->type(Type::integer()), [new ValidationRule('integer'), new ValidationRule('required_with', ['name', 'email'])], []);

        $modified = $contextual->modifiedBaseSchema();
        expect($modified)->not->toBeNull();

        $compiled = $modified->compile();

        expect($compiled)->toHaveKey('properties')
            ->and($compiled['properties'])->toHaveKey('name')
            ->and($compiled['properties'])->toHaveKey('email')
            ->and($compiled['properties'])->toHaveKey('age')
            ->and($compiled)->toHaveKey('allOf')
            ->and($compiled['allOf'])->toHaveCount(1);

        $condition = $compiled['allOf'][0];
        expect($condition['if'])->toBe(['anyOf' => [['required' => ['name']], ['required' => ['email']]]])
            ->and($condition['then'])->toBe(['required' => ['age']]);
    });

    it('preserves properties for mixed fields with and without required_with', function (): void {
        $parser = new RequiredWithParser();
        $baseSchema = LooseFluentDescriptor::withoutSchema()
            ->type(Type::object())
            ->properties(
                Property::create('name', LooseFluentDescriptor::withoutSchema()->type(Type::string())),
                Property::create('email', LooseFluentDescriptor::withoutSchema()->type(Type::string())),
                Property::create('age', LooseFluentDescriptor::withoutSchema()->type(Type::integer())),
            );

        $allRules = [
            'name' => [ValidationRuleNormalizer::RULES_KEY => [new ValidationRule('string'), new ValidationRule('required_with', ['email'])]],
            'email' => [ValidationRuleNormalizer::RULES_KEY => [new ValidationRule('string'), new ValidationRule('required_with', ['name'])]],
            'age' => [ValidationRuleNormalizer::RULES_KEY => [new ValidationRule('integer')]],
        ];

        $contextual = $parser->withContext($baseSchema, $allRules, null);

        $contextual('name', LooseFluentDescriptor::withoutSchema()->type(Type::string()), [new ValidationRule('string'), new ValidationRule('required_with', ['email'])], []);
        $contextual('email', LooseFluentDescriptor::withoutSchema()->type(Type::string()), [new ValidationRule('string'), new ValidationRule('required_with', ['name'])], []);
        $contextual('age', LooseFluentDescriptor::withoutSchema()->type(Type::integer()), [new ValidationRule('integer')], []);

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

    it('does not modify schema when no required_with rules exist', function (): void {
        $parser = new RequiredWithParser();
        $baseSchema = LooseFluentDescriptor::withoutSchema()
            ->type(Type::object())
            ->properties(
                Property::create('name', LooseFluentDescriptor::withoutSchema()->type(Type::string())),
            );

        $allRules = [
            'name' => [ValidationRuleNormalizer::RULES_KEY => [new ValidationRule('string'), new ValidationRule('required')]],
        ];

        $contextual = $parser->withContext($baseSchema, $allRules, null);

        $contextual('name', LooseFluentDescriptor::withoutSchema()->type(Type::string()), [new ValidationRule('string'), new ValidationRule('required')], []);

        $modified = $contextual->modifiedBaseSchema();
        expect($modified)->toBeNull();
    });

    it('preserves nullable type when required_with fields coexist', function (): void {
        $parser = new RequiredWithParser();
        $baseSchema = LooseFluentDescriptor::withoutSchema()
            ->type(Type::object())
            ->properties(
                Property::create('name', LooseFluentDescriptor::withoutSchema()->type(Type::string())),
                Property::create('email', LooseFluentDescriptor::withoutSchema()->type(Type::string())),
                Property::create('age', LooseFluentDescriptor::withoutSchema()->type(Type::integer(), Type::null())),
            );

        $allRules = [
            'name' => [ValidationRuleNormalizer::RULES_KEY => [new ValidationRule('string'), new ValidationRule('required_with', ['email'])]],
            'email' => [ValidationRuleNormalizer::RULES_KEY => [new ValidationRule('string'), new ValidationRule('required_with', ['name'])]],
            'age' => [ValidationRuleNormalizer::RULES_KEY => [new ValidationRule('nullable'), new ValidationRule('integer')]],
        ];

        $contextual = $parser->withContext($baseSchema, $allRules, null);

        $contextual('name', LooseFluentDescriptor::withoutSchema()->type(Type::string()), [new ValidationRule('string'), new ValidationRule('required_with', ['email'])], []);
        $contextual('email', LooseFluentDescriptor::withoutSchema()->type(Type::string()), [new ValidationRule('string'), new ValidationRule('required_with', ['name'])], []);
        $contextual('age', LooseFluentDescriptor::withoutSchema()->type(Type::integer(), Type::null()), [new ValidationRule('nullable'), new ValidationRule('integer')], []);

        $modified = $contextual->modifiedBaseSchema();
        expect($modified)->not->toBeNull();

        $compiled = $modified->compile();

        expect($compiled)->toHaveKey('properties')
            ->and($compiled['properties'])->toHaveKey('age')
            ->and($compiled['properties']['age'])->toHaveKey('type')
            ->and((array) $compiled['properties']['age']['type'])->toContain('null');
    });

    it('returns schema unchanged without context', function (): void {
        $parser = new RequiredWithParser();
        $schema = LooseFluentDescriptor::withoutSchema();

        $result = $parser('field', $schema, [new ValidationRule('required_with', ['other'])], []);

        expect($result)->toBe($schema);
    });
})->covers(RequiredWithParser::class);
