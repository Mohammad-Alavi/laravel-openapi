<?php

namespace MohammadAlavi\Laragen\RuleParsers;

use FluentJsonSchema\FluentSchema;
use LaravelRulesToSchema\Contracts\RuleParser;
use MohammadAlavi\Laragen\ExampleGenerator\ExampleProvider;

final readonly class AutogenOverride implements RuleParser
{
    public function __invoke(
        string $attribute,
        FluentSchema $schema,
        array $validationRules,
        array $nestedRuleset,
    ): array|FluentSchema|null {
        if (config()->boolean('laragen.laragen.autogen_example')) {
            foreach ($validationRules as $ruleArgs) {
                [$rule, $args] = $ruleArgs;

                // $ruleName = is_object($rule) ? get_class($rule) : $rule;

                if (is_string($rule) && ExampleProvider::has($rule)) {
                    $example = ExampleProvider::getExample($rule);

                    if ($example) {
                        $schema->examples($example->values());

                        if ($example->format()) {
                            $schema->format()->custom($example->format()->value());
                        }
                    }
                }
            }
        }

        return $schema;
    }
}
