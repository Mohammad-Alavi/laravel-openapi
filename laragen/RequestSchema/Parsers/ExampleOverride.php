<?php

namespace MohammadAlavi\Laragen\RequestSchema\Parsers;

use FluentJsonSchema\FluentSchema;
use MohammadAlavi\Laragen\RequestSchema\ExampleGenerator\Example;
use MohammadAlavi\Laragen\RequestSchema\ExampleGenerator\ExampleRegistry;

final class ExampleOverride implements ContextAwareRuleParser
{
    private FluentSchema|null $baseSchema = null;

    /** @var array<string, mixed>|null */
    private array|null $allRules = null;

    private string|null $request = null;

    public function __construct(
        private readonly ExampleRegistry $registry,
    ) {
    }

    public function withContext(FluentSchema $baseSchema, array $allRules, string|null $request): static
    {
        $clone = clone $this;
        $clone->baseSchema = $baseSchema;
        $clone->allRules = $allRules;
        $clone->request = $request;

        return $clone;
    }

    public function __invoke(
        string $attribute,
        FluentSchema $schema,
        array $validationRules,
        array $nestedRuleset,
    ): array|FluentSchema|null {
        if (!config()->boolean('laragen.autogen.example')) {
            return $schema;
        }

        if (null === $this->baseSchema || null === $this->allRules) {
            return $schema;
        }

        foreach ($validationRules as $ruleArgs) {
            [$rule, $args] = $ruleArgs;

            $ruleName = is_object($rule) ? get_class($rule) : $rule;

            if ($this->registry->has($ruleName)) {
                $example = $this->registry->get($ruleName);

                if (is_string($example) && !is_null($this->request)) {
                    /** @var Example $instance */
                    $instance = resolve(
                        $example,
                        [
                            'attribute' => $attribute,
                            'schema' => $schema,
                            'validationRules' => $validationRules,
                            'nestedRuleset' => $nestedRuleset,
                            'baseSchema' => $this->baseSchema,
                            'allRules' => $this->allRules,
                        ],
                    );

                    if ($instance->shouldBeGeneratedFor($this->request, $attribute)) {
                        $currentExamples = $schema->getSchemaDTO()->examples ?? [];
                        $schema->examples([...$currentExamples, ...$instance->values()]);

                        $format = $instance->format();
                        if (!is_null($format)) {
                            $schema->format()->custom($format->value());
                        }
                    }
                }
            }
        }

        return $schema;
    }
}
