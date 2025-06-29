<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\License;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\License\Fields\Identifier;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Arr;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Name;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\URL;
use Webmozart\Assert\Assert;

final class License extends ExtensibleObject
{
    private Identifier|null $identifier = null;
    private URL|null $url = null;

    private function __construct(
        private Name $name,
        Identifier|URL|null $license = null,
    ) {
        if ($license instanceof Identifier) {
            $this->identifier = $license;
        } elseif ($license instanceof URL) {
            $this->url = $license;
        }
    }

    public static function create(Name $name, Identifier|URL|null $license = null): self
    {
        return new self($name, $license);
    }

    public function name(Name $name): self
    {
        $clone = clone $this;

        $clone->name = $name;

        return $clone;
    }

    public function identifier(Identifier|null $identifier): self
    {
        Assert::null(
            $this->url,
            'identifier and url fields are mutually exclusive.',
        );

        $clone = clone $this;

        $clone->identifier = $identifier;

        return $clone;
    }

    public function url(URL|null $url): self
    {
        Assert::null(
            $this->identifier,
            'url and identifier fields are mutually exclusive.',
        );

        $clone = clone $this;

        $clone->url = $url;

        return $clone;
    }

    public function toArray(): array
    {
        return Arr::filter([
            'name' => $this->name,
            ...$this->eitherIdentifierOrUrl(),
        ]);
    }

    private function eitherIdentifierOrUrl(): array
    {
        if (!is_null($this->identifier)) {
            return ['identifier' => $this->identifier];
        }

        if (!is_null($this->url)) {
            return ['url' => $this->url];
        }

        return [];
    }
}
