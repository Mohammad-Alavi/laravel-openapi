<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Formats\DefinedFormat;
use MohammadAlavi\ObjectOrientedJSONSchema\Validation\Contracts\FormatValidator;

/**
 * Registry for managing format strings and their optional validators.
 *
 * Use cases:
 * - Register known format strings for documentation
 * - Optionally attach validators to format strings
 * - Check if a format is a known/standard format
 * - List all registered formats
 *
 * Note: This library is primarily a schema builder, not a validator.
 * FormatRegistry provides the infrastructure for validation, but actual
 * validation is optional and should be implemented per-format as needed.
 *
 * @see https://json-schema.org/understanding-json-schema/reference/string#format
 */
final class FormatRegistry
{
    /** @var array<string, FormatValidator|null> Format name => validator (null means annotation-only) */
    private array $formats = [];

    /**
     * Register a format with an optional validator.
     *
     * @param string $format The format string (e.g., 'email', 'phone-number')
     * @param FormatValidator|null $validator Optional validator for this format
     */
    public function register(string $format, FormatValidator|null $validator = null): self
    {
        $this->formats[$format] = $validator;

        return $this;
    }

    /**
     * Register multiple formats from a DefinedFormat enum.
     *
     * @param class-string<DefinedFormat> $enumClass The enum class (must be a backed enum)
     */
    public function registerFromEnum(string $enumClass): self
    {
        if (!enum_exists($enumClass)) {
            throw new \InvalidArgumentException("{$enumClass} is not an enum");
        }

        foreach ($enumClass::cases() as $case) {
            $this->formats[$case->value()] = null;
        }

        return $this;
    }

    /**
     * Register a format with a callable validator.
     *
     * @param string $format The format string
     * @param callable(mixed): bool $validator Callable that returns true if valid
     * @param string $errorMessage Error message when validation fails
     */
    public function registerWithCallable(
        string $format,
        callable $validator,
        string $errorMessage = 'Invalid format',
    ): self {
        $this->formats[$format] = new class($validator, $errorMessage) implements FormatValidator {
            public function __construct(
                /** @var callable(mixed): bool */
                private $validator,
                private string $errorMessage,
            ) {
            }

            public function validate(mixed $value): bool
            {
                return ($this->validator)($value);
            }

            public function errorMessage(): string
            {
                return $this->errorMessage;
            }
        };

        return $this;
    }

    /**
     * Check if a format is registered (known).
     */
    public function isKnown(string $format): bool
    {
        return array_key_exists($format, $this->formats);
    }

    /**
     * Check if a format has a validator attached.
     */
    public function hasValidator(string $format): bool
    {
        return isset($this->formats[$format]);
    }

    /**
     * Get the validator for a format.
     */
    public function getValidator(string $format): FormatValidator|null
    {
        return $this->formats[$format] ?? null;
    }

    /**
     * Validate a value against a format.
     *
     * Returns true if:
     * - Format is not registered (unknown formats pass by default per JSON Schema spec)
     * - Format has no validator (annotation-only)
     * - Validator returns true
     *
     * Returns false only if format has a validator that returns false.
     */
    public function validate(string $format, mixed $value): bool
    {
        $validator = $this->getValidator($format);

        if (null === $validator) {
            return true;
        }

        return $validator->validate($value);
    }

    /**
     * Get all registered format names.
     *
     * @return string[]
     */
    public function getFormats(): array
    {
        return array_keys($this->formats);
    }

    /**
     * Get all formats that have validators.
     *
     * @return string[]
     */
    public function getValidatableFormats(): array
    {
        return array_keys(array_filter($this->formats, static fn ($v) => null !== $v));
    }

    /**
     * Get all annotation-only formats (no validator).
     *
     * @return string[]
     */
    public function getAnnotationOnlyFormats(): array
    {
        return array_keys(array_filter($this->formats, static fn ($v) => null === $v));
    }

    /**
     * Remove a format from the registry.
     */
    public function unregister(string $format): self
    {
        unset($this->formats[$format]);

        return $this;
    }

    /**
     * Clear all registered formats.
     */
    public function clear(): self
    {
        $this->formats = [];

        return $this;
    }

    /**
     * Create a registry pre-populated with JSON Schema standard formats.
     *
     * Note: By default, these are registered as annotation-only (no validators).
     * Add validators as needed for your use case.
     */
    public static function withStandardFormats(): self
    {
        $registry = new self();

        // Date and time formats
        $registry->register('date-time');
        $registry->register('date');
        $registry->register('time');
        $registry->register('duration');

        // Email formats
        $registry->register('email');
        $registry->register('idn-email');

        // Hostname formats
        $registry->register('hostname');
        $registry->register('idn-hostname');

        // IP address formats
        $registry->register('ipv4');
        $registry->register('ipv6');

        // URI formats
        $registry->register('uri');
        $registry->register('uri-reference');
        $registry->register('iri');
        $registry->register('iri-reference');
        $registry->register('uri-template');

        // Other formats
        $registry->register('uuid');
        $registry->register('json-pointer');
        $registry->register('relative-json-pointer');
        $registry->register('regex');

        return $registry;
    }
}
