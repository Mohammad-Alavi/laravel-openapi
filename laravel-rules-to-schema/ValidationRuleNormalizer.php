<?php

declare(strict_types=1);

namespace MohammadAlavi\LaravelRulesToSchema;

use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationRuleParser;
use ReflectionClass;

final class ValidationRuleNormalizer
{
    public const RULES_KEY = '##_VALIDATION_RULES_##';

    private array $rules;

    public function __construct(array $rules)
    {
        $this->rules = $this->standardizeRules($rules);
    }

    public function getRules(): array
    {
        return $this->rules;
    }

    private function standardizeRules(array $rawRules): array
    {
        $nestedRules = [];

        foreach ($rawRules as $name => $rules) {
            if (is_string($rules)) {
                $rules = $this->splitStringToRuleset($rules);
            }

            $rules = $this->normalizeRuleset($rules);

            Arr::set($nestedRules, "{$name}." . self::RULES_KEY, $rules);
        }

        return $nestedRules;
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
