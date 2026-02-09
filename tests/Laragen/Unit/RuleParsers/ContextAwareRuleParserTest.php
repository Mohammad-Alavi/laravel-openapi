<?php

declare(strict_types=1);

use FluentJsonSchema\FluentSchema;
use LaravelRulesToSchema\Contracts\RuleParser;
use MohammadAlavi\Laragen\RuleParsers\ContextAwareRuleParser;

describe(class_basename(ContextAwareRuleParser::class), function (): void {
    it('extends the RuleParser interface', function (): void {
        expect(is_subclass_of(ContextAwareRuleParser::class, RuleParser::class))->toBeTrue();
    });

    it('provides context to parsers that need it', function (): void {
        $parser = new class implements ContextAwareRuleParser {
            public FluentSchema|null $receivedBaseSchema = null;

            public array|null $receivedAllRules = null;

            public string|null $receivedRequest = null;

            public function withContext(FluentSchema $baseSchema, array $allRules, string|null $request): static
            {
                $clone = clone $this;
                $clone->receivedBaseSchema = $baseSchema;
                $clone->receivedAllRules = $allRules;
                $clone->receivedRequest = $request;

                return $clone;
            }

            public function __invoke(
                string $attribute,
                FluentSchema $schema,
                array $validationRules,
                array $nestedRuleset,
            ): array|FluentSchema|null {
                return $schema;
            }
        };

        $baseSchema = FluentSchema::make();
        $allRules = ['name' => ['string']];

        $contextual = $parser->withContext($baseSchema, $allRules, 'MyFormRequest');

        expect($contextual->receivedBaseSchema)->toBe($baseSchema)
            ->and($contextual->receivedAllRules)->toBe($allRules)
            ->and($contextual->receivedRequest)->toBe('MyFormRequest');
    });

    it('returns a new instance preserving immutability', function (): void {
        $parser = new class implements ContextAwareRuleParser {
            public FluentSchema|null $receivedBaseSchema = null;

            public function withContext(FluentSchema $baseSchema, array $allRules, string|null $request): static
            {
                $clone = clone $this;
                $clone->receivedBaseSchema = $baseSchema;

                return $clone;
            }

            public function __invoke(
                string $attribute,
                FluentSchema $schema,
                array $validationRules,
                array $nestedRuleset,
            ): array|FluentSchema|null {
                return $schema;
            }
        };

        $contextual = $parser->withContext(FluentSchema::make(), [], null);

        expect($parser->receivedBaseSchema)->toBeNull()
            ->and($contextual->receivedBaseSchema)->toBeInstanceOf(FluentSchema::class);
    });
})->covers(ContextAwareRuleParser::class);
