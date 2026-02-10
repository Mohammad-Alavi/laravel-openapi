<?php

declare(strict_types=1);

use MohammadAlavi\LaravelRulesToSchema\Parsers\ConditionalRequiredParser;
use MohammadAlavi\LaravelRulesToSchema\ValidationRule;
use MohammadAlavi\LaravelRulesToSchema\ValidationRuleNormalizer;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\LooseFluentDescriptor;

describe(class_basename(ConditionalRequiredParser::class), function (): void {
    it('generates if/then for required_if rule', function (): void {
        $parser = new ConditionalRequiredParser();
        $baseSchema = LooseFluentDescriptor::withoutSchema();
        $allRules = [
            'name' => [ValidationRuleNormalizer::RULES_KEY => [new ValidationRule('string'), new ValidationRule('required_if', ['role', 'admin'])]],
        ];

        $contextual = $parser->withContext($baseSchema, $allRules, null);
        $schema = LooseFluentDescriptor::withoutSchema();

        $result = $contextual('name', $schema, [new ValidationRule('string'), new ValidationRule('required_if', ['role', 'admin'])], []);

        $compiled = $result->compile();

        expect($compiled)->toHaveKey('if')
            ->and($compiled['if']['properties']['role'])->toBe(['const' => 'admin'])
            ->and($compiled['then'])->toBe(['required' => ['name']]);
    });

    it('generates if/then for required_unless rule', function (): void {
        $parser = new ConditionalRequiredParser();
        $baseSchema = LooseFluentDescriptor::withoutSchema();
        $allRules = [
            'name' => [ValidationRuleNormalizer::RULES_KEY => [new ValidationRule('string'), new ValidationRule('required_unless', ['role', 'guest'])]],
        ];

        $contextual = $parser->withContext($baseSchema, $allRules, null);
        $schema = LooseFluentDescriptor::withoutSchema();

        $result = $contextual('name', $schema, [new ValidationRule('string'), new ValidationRule('required_unless', ['role', 'guest'])], []);

        $compiled = $result->compile();

        expect($compiled)->toHaveKey('if')
            ->and($compiled['if']['properties']['role'])->toBe(['const' => 'guest'])
            ->and($compiled['else'])->toBe(['required' => ['name']]);
    });

    it('generates if/then for required_with_all rule', function (): void {
        $parser = new ConditionalRequiredParser();
        $baseSchema = LooseFluentDescriptor::withoutSchema();
        $allRules = [
            'city' => [ValidationRuleNormalizer::RULES_KEY => [new ValidationRule('string'), new ValidationRule('required_with_all', ['street', 'zip'])]],
        ];

        $contextual = $parser->withContext($baseSchema, $allRules, null);
        $schema = LooseFluentDescriptor::withoutSchema();

        $result = $contextual('city', $schema, [new ValidationRule('string'), new ValidationRule('required_with_all', ['street', 'zip'])], []);

        $compiled = $result->compile();

        expect($compiled)->toHaveKey('if')
            ->and($compiled['if'])->toBe(['required' => ['street', 'zip']])
            ->and($compiled['then'])->toBe(['required' => ['city']]);
    });

    it('generates if/then for required_without_all rule', function (): void {
        $parser = new ConditionalRequiredParser();
        $baseSchema = LooseFluentDescriptor::withoutSchema();
        $allRules = [
            'email' => [ValidationRuleNormalizer::RULES_KEY => [new ValidationRule('string'), new ValidationRule('required_without_all', ['phone', 'fax'])]],
        ];

        $contextual = $parser->withContext($baseSchema, $allRules, null);
        $schema = LooseFluentDescriptor::withoutSchema();

        $result = $contextual('email', $schema, [new ValidationRule('string'), new ValidationRule('required_without_all', ['phone', 'fax'])], []);

        $compiled = $result->compile();

        expect($compiled)->toHaveKey('if')
            ->and($compiled['then'])->toBe(['required' => ['email']]);
    });

    it('generates if/then for required_if_accepted rule', function (): void {
        $parser = new ConditionalRequiredParser();
        $baseSchema = LooseFluentDescriptor::withoutSchema();
        $allRules = [
            'reason' => [ValidationRuleNormalizer::RULES_KEY => [new ValidationRule('string'), new ValidationRule('required_if_accepted', ['terms'])]],
        ];

        $contextual = $parser->withContext($baseSchema, $allRules, null);
        $schema = LooseFluentDescriptor::withoutSchema();

        $result = $contextual('reason', $schema, [new ValidationRule('string'), new ValidationRule('required_if_accepted', ['terms'])], []);

        $compiled = $result->compile();

        expect($compiled)->toHaveKey('if')
            ->and($compiled['if']['properties']['terms'])->toBe(['const' => true])
            ->and($compiled['then'])->toBe(['required' => ['reason']]);
    });

    it('generates if/then for required_if_declined rule', function (): void {
        $parser = new ConditionalRequiredParser();
        $baseSchema = LooseFluentDescriptor::withoutSchema();
        $allRules = [
            'reason' => [ValidationRuleNormalizer::RULES_KEY => [new ValidationRule('string'), new ValidationRule('required_if_declined', ['agree'])]],
        ];

        $contextual = $parser->withContext($baseSchema, $allRules, null);
        $schema = LooseFluentDescriptor::withoutSchema();

        $result = $contextual('reason', $schema, [new ValidationRule('string'), new ValidationRule('required_if_declined', ['agree'])], []);

        $compiled = $result->compile();

        expect($compiled)->toHaveKey('if')
            ->and($compiled['if']['properties']['agree'])->toBe(['const' => false])
            ->and($compiled['then'])->toBe(['required' => ['reason']]);
    });

    it('does not modify schema when no conditional required rules exist', function (): void {
        $parser = new ConditionalRequiredParser();
        $baseSchema = LooseFluentDescriptor::withoutSchema();
        $allRules = [
            'name' => [ValidationRuleNormalizer::RULES_KEY => [new ValidationRule('string'), new ValidationRule('required')]],
        ];

        $contextual = $parser->withContext($baseSchema, $allRules, null);
        $schema = LooseFluentDescriptor::withoutSchema();

        $result = $contextual('name', $schema, [new ValidationRule('string'), new ValidationRule('required')], []);

        $compiled = $result->compile();

        expect($compiled)->not->toHaveKey('if');
    });

    it('returns schema unchanged without context', function (): void {
        $parser = new ConditionalRequiredParser();
        $schema = LooseFluentDescriptor::withoutSchema();

        $result = $parser('field', $schema, [new ValidationRule('required_if', ['role', 'admin'])], []);

        $compiled = $result->compile();

        expect($compiled)->not->toHaveKey('if');
    });

    it('handles required_if with multiple value pairs', function (): void {
        $parser = new ConditionalRequiredParser();
        $baseSchema = LooseFluentDescriptor::withoutSchema();
        $allRules = [
            'name' => [ValidationRuleNormalizer::RULES_KEY => [new ValidationRule('required_if', ['role', 'admin', 'role', 'super'])]],
        ];

        $contextual = $parser->withContext($baseSchema, $allRules, null);
        $schema = LooseFluentDescriptor::withoutSchema();

        $result = $contextual('name', $schema, [new ValidationRule('required_if', ['role', 'admin', 'role', 'super'])], []);

        $compiled = $result->compile();

        expect($compiled)->toHaveKey('if');
    });
})->covers(ConditionalRequiredParser::class);
