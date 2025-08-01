<?php

namespace MohammadAlavi\Laragen\ExampleGenerator;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Contracts\Validation\ValidationRule;
use Webmozart\Assert\Assert;

final class ExampleProvider
{
    /** @var array<string, class-string<Example> */
    private static array $examples = [];

    /**
     * Register an example for a specific rule.
     *
     * @param class-string<Example> ...$example
     */
    public static function addExample(string ...$example): void
    {
        Assert::allIsAOf($example, Example::class);
        foreach ($example as $ex) {
            self::$examples[app($ex)->rule()] = $ex;
        }
    }

    public static function getExample(string $rule): Example|null
    {
        if (self::has($rule)) {
            $example = self::$examples[$rule];
            if (class_exists($example)) {
                return app($example);
            }
        }

        return null;
    }

    public static function has(string|Rule|ValidationRule $rule): bool
    {
        // TODO: implement support for Rule and ValidationRule instances
        return array_key_exists($rule, self::$examples);
    }
}
