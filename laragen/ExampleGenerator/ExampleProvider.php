<?php

namespace MohammadAlavi\Laragen\ExampleGenerator;

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
        Assert::allClassExists($example, Example::class);
        foreach ($example as $ex) {
            self::$examples[$ex::rule()] = $ex;
        }
    }

    /**
     * Get an example for a specific rule.
     *
     * @param string $rule The rule name or class
     *
     * @return class-string<Example>|null
     */
    public static function getExample(string $rule): string|null
    {
        if (self::has($rule)) {
            return self::$examples[$rule];
        }

        return null;
    }

    public static function has(string $rule): bool
    {
        return array_key_exists($rule, self::$examples);
    }
}
