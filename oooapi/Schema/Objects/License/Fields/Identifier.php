<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\License\Fields;

use MohammadAlavi\ObjectOrientedOpenAPI\Support\StringField;
use Webmozart\Assert\Assert;

/**
 * SPDX License Identifier.
 *
 * An SPDX license expression for the API. The identifier field is mutually
 * exclusive of the url field in the License Object.
 *
 * @see https://spec.openapis.org/oas/v3.2.0#license-object
 * @see https://spdx.org/licenses/
 */
final readonly class Identifier extends StringField
{
    private function __construct(
        private string $value,
    ) {
        Assert::notEmpty($value, 'License identifier cannot be empty.');
        Assert::regex(
            $value,
            '/^[A-Za-z0-9.\-+]+$/',
            sprintf('License identifier "%s" contains invalid characters. Use SPDX format.', $value),
        );
    }

    public static function create(string $value): self
    {
        return new self($value);
    }

    /**
     * MIT License.
     */
    public static function mit(): self
    {
        return new self('MIT');
    }

    /**
     * Apache License 2.0.
     */
    public static function apache2(): self
    {
        return new self('Apache-2.0');
    }

    /**
     * GNU General Public License v3.0.
     */
    public static function gpl3(): self
    {
        return new self('GPL-3.0');
    }

    /**
     * BSD 3-Clause License.
     */
    public static function bsd3(): self
    {
        return new self('BSD-3-Clause');
    }

    /**
     * ISC License.
     */
    public static function isc(): self
    {
        return new self('ISC');
    }

    /**
     * Mozilla Public License 2.0.
     */
    public static function mpl2(): self
    {
        return new self('MPL-2.0');
    }

    /**
     * The Unlicense.
     */
    public static function unlicense(): self
    {
        return new self('Unlicense');
    }

    public function value(): string
    {
        return $this->value;
    }
}
