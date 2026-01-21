<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Support\Rules;

use Webmozart\Assert\Assert;

/**
 * Validates JSON Pointer syntax according to RFC 6901.
 *
 * A JSON Pointer is a string of zero or more reference tokens,
 * each prefixed by a '/' character.
 *
 * Special escape sequences:
 * - ~0 represents '~'
 * - ~1 represents '/'
 *
 * @see https://datatracker.ietf.org/doc/html/rfc6901
 */
final readonly class JsonPointer
{
    public function __construct(
        private string $value,
    ) {
        // Empty string is valid (refers to whole document)
        if ('' === $this->value) {
            return;
        }

        // Non-empty pointer must start with '/'
        Assert::startsWith(
            $this->value,
            '/',
            sprintf('JSON Pointer "%s" must start with "/" or be empty.', $this->value),
        );

        // Validate escape sequences: ~ must be followed by 0 or 1
        $this->validateEscapeSequences();
    }

    private function validateEscapeSequences(): void
    {
        $length = strlen($this->value);

        for ($i = 0; $i < $length; ++$i) {
            if ('~' === $this->value[$i]) {
                // ~ must be followed by 0 or 1
                Assert::true(
                    $i + 1 < $length && in_array($this->value[$i + 1], ['0', '1'], true),
                    sprintf(
                        'Invalid JSON Pointer "%s": "~" at position %d must be followed by "0" or "1".',
                        $this->value,
                        $i,
                    ),
                );
            }
        }
    }
}
