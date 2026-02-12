<?php

use MohammadAlavi\LaravelRulesToSchema\Contracts\ContextAwareRuleParser;
use MohammadAlavi\LaravelRulesToSchema\Contracts\RuleParser;
use MohammadAlavi\LaravelRulesToSchema\NestedRuleset;
use MohammadAlavi\LaravelRulesToSchema\ParseResult;
use MohammadAlavi\LaravelRulesToSchema\ValidationRule;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\LooseFluentDescriptor;

describe(class_basename(ContextAwareRuleParser::class), function (): void {
    it('extends the RuleParser interface', function (): void {
        expect(is_subclass_of(ContextAwareRuleParser::class, RuleParser::class))->toBeTrue();
    });

    it('provides context to parsers that need it', function (): void {
        $parser = new class implements ContextAwareRuleParser {
            public LooseFluentDescriptor|null $receivedBaseSchema = null;

            public array|null $receivedAllRules = null;

            public string|null $receivedRequest = null;

            public function withContext(LooseFluentDescriptor $baseSchema, array $allRules, string|null $request): static
            {
                $clone = clone $this;
                $clone->receivedBaseSchema = $baseSchema;
                $clone->receivedAllRules = $allRules;
                $clone->receivedRequest = $request;

                return $clone;
            }

            public function modifiedBaseSchema(): LooseFluentDescriptor|null
            {
                return null;
            }

            public function __invoke(
                string $attribute,
                LooseFluentDescriptor $schema,
                array $validationRules,
                NestedRuleset $nestedRuleset,
            ): ParseResult {
                return ParseResult::single($schema);
            }
        };

        $baseSchema = LooseFluentDescriptor::withoutSchema();
        $allRules = ['name' => new NestedRuleset([new ValidationRule('string')])];

        $contextual = $parser->withContext($baseSchema, $allRules, 'MyFormRequest');

        expect($contextual->receivedBaseSchema)->toBe($baseSchema)
            ->and($contextual->receivedAllRules)->toBe($allRules)
            ->and($contextual->receivedRequest)->toBe('MyFormRequest');
    });

    it('returns a new instance preserving immutability', function (): void {
        $parser = new class implements ContextAwareRuleParser {
            public LooseFluentDescriptor|null $receivedBaseSchema = null;

            public function withContext(LooseFluentDescriptor $baseSchema, array $allRules, string|null $request): static
            {
                $clone = clone $this;
                $clone->receivedBaseSchema = $baseSchema;

                return $clone;
            }

            public function modifiedBaseSchema(): LooseFluentDescriptor|null
            {
                return null;
            }

            public function __invoke(
                string $attribute,
                LooseFluentDescriptor $schema,
                array $validationRules,
                NestedRuleset $nestedRuleset,
            ): ParseResult {
                return ParseResult::single($schema);
            }
        };

        $contextual = $parser->withContext(LooseFluentDescriptor::withoutSchema(), [], null);

        expect($parser->receivedBaseSchema)->toBeNull()
            ->and($contextual->receivedBaseSchema)->toBeInstanceOf(LooseFluentDescriptor::class);
    });
})->covers(ContextAwareRuleParser::class);
