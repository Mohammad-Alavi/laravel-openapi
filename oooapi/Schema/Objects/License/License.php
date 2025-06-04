<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\License;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\License\Fields\Identifier;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\License\Fields\Name;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\License\Fields\URL;
use MohammadAlavi\ObjectOrientedOpenAPI\Utilities\Arr;
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

    public static function create(Name $name, Identifier|URL|null $license): self
    {
        return new self($name, $license);
    }

    public function name(Name|null $name): self
    {
        $clone = clone $this;

        $clone->name = $name;

        return $clone;
    }

    public function identifier(Identifier|null $identifier): self
    {
        Assert::null(
            $this->url,
            'Identifier and URL fields are mutually exclusive. Please unset the URL field before setting the identifier.',
        );

        $clone = clone $this;

        $clone->identifier = $identifier;

        return $clone;
    }

    public function url(URL|null $url): self
    {
        Assert::null(
            $this->identifier,
            'URL and Identifier fields are mutually exclusive. Please unset the identifier field before setting the URL.',
        );

        $clone = clone $this;

        $clone->url = $url;

        return $clone;
    }

    protected function toArray(): array
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
