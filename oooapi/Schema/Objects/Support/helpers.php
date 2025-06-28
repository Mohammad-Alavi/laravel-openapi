<?php

if (!function_exists('blank')) {
    /**
     * Determine if the given value is "blank".
     *
     * @phpstan-assert-if-false !=null|'' $value
     *
     * @phpstan-assert-if-true !=numeric|bool $value
     */
    function blank($value): bool
    {
        if (is_null($value)) {
            return true;
        }

        if (is_string($value)) {
            return '' === trim($value);
        }

        if (is_numeric($value) || is_bool($value)) {
            return false;
        }

        if ($value instanceof Countable) {
            return 0 === count($value);
        }

        if ($value instanceof Stringable) {
            return '' === trim((string) $value);
        }

        return empty($value);
    }
}

if (!function_exists('value')) {
    /**
     * Return the default value of the given value.
     *
     * @template TValue
     * @template TArgs
     *
     * @param TValue|Closure(TArgs): TValue $value
     * @param TArgs ...$args
     *
     * @return TValue
     */
    function value($value, ...$args)
    {
        if ($value instanceof Closure) {
            return $value(...$args);
        }

        return $value;
    }
}

if (!function_exists('when')) {
    /**
     * Return a value if the given condition is true.
     *
     * @param Closure|mixed $value
     * @param Closure|mixed $default
     */
    function when($condition, $value, $default = null)
    {
        $condition = $condition instanceof Closure ? $condition() : $condition;

        if ($condition) {
            return value($value, $condition);
        }

        return value($default, $condition);
    }
}
