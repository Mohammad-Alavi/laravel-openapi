<?php

namespace MohammadAlavi\LaravelRulesToSchema;

use Illuminate\Validation\ValidationRuleParser;
use ReflectionClass;

final class ValidationRuleNormalizer
{
    public const RULES_KEY = '##_VALIDATION_RULES_##';

    /** @var array<string, NestedRuleset> */
    private array $rules;

    public function __construct(array $rules)
    {
        $this->rules = $this->standardizeRules($rules);
    }

    /** @return array<string, NestedRuleset> */
    public function getRules(): array
    {
        return $this->rules;
    }

    /** @return array<string, NestedRuleset> */
    private function standardizeRules(array $rawRules): array
    {
        $flat = [];

        foreach ($rawRules as $name => $rules) {
            if (is_string($rules)) {
                $rules = $this->splitStringToRuleset($rules);
            }

            $flat[$name] = $this->normalizeRuleset($rules);
        }

        return $this->buildNestedRulesets($flat);
    }

    /**
     * @param array<string, list<ValidationRule>> $flat
     *
     * @return array<string, NestedRuleset>
     */
    private function buildNestedRulesets(array $flat): array
    {
        $topLevel = [];
        $children = [];

        foreach ($flat as $name => $rules) {
            $parts = explode('.', $name);

            if (1 === count($parts)) {
                $topLevel[$name] = $rules;
            } else {
                $parent = $parts[0];
                $childKey = implode('.', array_slice($parts, 1));
                $children[$parent][$childKey] = $rules;
            }
        }

        $result = [];

        foreach ($topLevel as $name => $rules) {
            $nested = isset($children[$name])
                ? $this->buildNestedRulesets($children[$name])
                : [];

            $result[$name] = new NestedRuleset($rules, $nested);
        }

        foreach ($children as $parent => $childRules) {
            if (!isset($topLevel[$parent])) {
                $result[$parent] = new NestedRuleset([], $this->buildNestedRulesets($childRules));
            }
        }

        return $result;
    }

    /** @return list<ValidationRule> */
    private function normalizeRuleset(array $rules): array
    {
        $normalized = [];

        foreach ($rules as $rule) {
            if (is_string($rule)) {
                $normalized[] = $this->parseStringRuleArgs($rule);
            } else {
                $normalized[] = new ValidationRule($rule);
            }
        }

        return $normalized;
    }

    private function splitStringToRuleset(string $rules): array
    {
        $parser = new ValidationRuleParser([]);
        $method = (new ReflectionClass($parser))->getMethod('explodeExplicitRule');
        $method->setAccessible(true);

        return $method->invokeArgs($parser, [$rules, null]);
    }

    private function parseStringRuleArgs(string $rule): ValidationRule
    {
        $parser = new ValidationRuleParser([]);
        $method = (new ReflectionClass($parser))->getMethod('parseParameters');
        $method->setAccessible(true);

        $parameters = [];

        if (str_contains($rule, ':')) {
            [$rule, $parameter] = explode(':', $rule, 2);

            $parameters = $method->invokeArgs($parser, [$rule, $parameter]);
        }

        return new ValidationRule(trim($rule), $parameters);
    }
}
