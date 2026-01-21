<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\License;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\License\Fields\Identifier;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Arr;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Name;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\URL;
use Webmozart\Assert\Assert;

/**
 * License Object.
 *
 * License information for the exposed API. The name field is required.
 * The identifier and url fields are mutually exclusive.
 *
 * @see https://spec.openapis.org/oas/v3.2.0#license-object
 * @see https://spdx.org/licenses/ SPDX License List
 */
final class License extends ExtensibleObject
{
    private Identifier|null $identifier = null;
    private URL|null $url = null;

    private function __construct(
        private readonly Name $name,
    ) {
    }

    public function identifier(string $identifier): self
    {
        Assert::null(
            $this->url,
            'identifier and url fields are mutually exclusive.',
        );

        $clone = clone $this;

        $clone->identifier = Identifier::create($identifier);

        return $clone;
    }

    public static function create(string $name): self
    {
        return new self(Name::create($name));
    }

    /**
     * MIT License.
     *
     * @see https://opensource.org/licenses/MIT
     */
    public static function mit(): self
    {
        return (new self(Name::create('MIT License')))
            ->identifier('MIT');
    }

    /**
     * Apache License 2.0.
     *
     * @see https://www.apache.org/licenses/LICENSE-2.0
     */
    public static function apache2(): self
    {
        return (new self(Name::create('Apache License 2.0')))
            ->identifier('Apache-2.0');
    }

    /**
     * GNU General Public License v3.0.
     *
     * @see https://www.gnu.org/licenses/gpl-3.0.html
     */
    public static function gpl3(): self
    {
        return (new self(Name::create('GNU General Public License v3.0')))
            ->identifier('GPL-3.0');
    }

    /**
     * BSD 3-Clause License.
     *
     * @see https://opensource.org/licenses/BSD-3-Clause
     */
    public static function bsd3(): self
    {
        return (new self(Name::create('BSD 3-Clause License')))
            ->identifier('BSD-3-Clause');
    }

    /**
     * ISC License.
     *
     * @see https://opensource.org/licenses/ISC
     */
    public static function isc(): self
    {
        return (new self(Name::create('ISC License')))
            ->identifier('ISC');
    }

    /**
     * Mozilla Public License 2.0.
     *
     * @see https://www.mozilla.org/en-US/MPL/2.0/
     */
    public static function mpl2(): self
    {
        return (new self(Name::create('Mozilla Public License 2.0')))
            ->identifier('MPL-2.0');
    }

    /**
     * The Unlicense.
     *
     * @see https://unlicense.org/
     */
    public static function unlicense(): self
    {
        return (new self(Name::create('The Unlicense')))
            ->identifier('Unlicense');
    }

    /**
     * Proprietary license with custom URL.
     */
    public static function proprietary(string $licenseUrl): self
    {
        return (new self(Name::create('Proprietary')))
            ->url($licenseUrl);
    }

    public function url(string $url): self
    {
        Assert::null(
            $this->identifier,
            'url and identifier fields are mutually exclusive.',
        );

        $clone = clone $this;

        $clone->url = URL::create($url);

        return $clone;
    }

    public function toArray(): array
    {
        return Arr::filter([
            'name' => $this->name,
            'identifier' => $this->identifier,
            'url' => $this->url,
        ]);
    }
}
