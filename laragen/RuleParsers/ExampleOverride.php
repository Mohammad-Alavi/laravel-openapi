<?php

namespace MohammadAlavi\Laragen\RuleParsers;

use FluentJsonSchema\FluentSchema;
use LaravelRulesToSchema\Contracts\RuleParser;
use MohammadAlavi\Laragen\ExampleGenerator\Example;
use MohammadAlavi\Laragen\ExampleGenerator\ExampleProvider;

final readonly class ExampleOverride implements RuleParser
{
    public function __invoke(
        string $attribute,
        FluentSchema $schema,
        array $validationRules,
        array $nestedRuleset,
    ): array|FluentSchema|null {
        if (config()->boolean('laragen.laragen.autogen_example')) {
            if (func_num_args() < 7) {
                return $schema;
            }
            /** @var FluentSchema $baseSchema */
            $baseSchema = func_get_arg(4);
            /** @var array<int, array{0: string|object, 1: array}> $allRules */
            $allRules = func_get_arg(5);
            /** @var string|null $request */
            $request = func_get_arg(6);
            if (!($baseSchema instanceof FluentSchema) || !is_array($allRules)) {
                return $schema;
            }

            foreach ($validationRules as $ruleArgs) {
                [$rule, $args] = $ruleArgs;

                $ruleName = is_object($rule) ? get_class($rule) : $rule;

                if (ExampleProvider::has($ruleName)) {
                    $example = ExampleProvider::getExample($ruleName);

                    if (is_string($example) && !is_null($request)) {
                        /** @var Example $instance */
                        $instance = resolve(
                            $example,
                            compact(
                                'attribute',
                                'schema',
                                'validationRules',
                                'nestedRuleset',
                                'baseSchema',
                                'allRules',
                            ),
                        );

                        if ($instance->shouldBeGeneratedFor($request, $attribute)) {
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
        }

        return $schema;
    }
}
