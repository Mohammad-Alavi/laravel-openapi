<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Contact;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Contact\Fields\Email;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Contact\Fields\Name;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Contact\Fields\URL;
use MohammadAlavi\ObjectOrientedOpenAPI\Utilities\Arr;

final class Contact extends ExtensibleObject
{
    private Name|null $name = null;
    private URL|null $url = null;
    private Email|null $email = null;

    private function __construct()
    {
    }

    public static function create(): self
    {
        return new self();
    }

    public function name(Name|null $name): self
    {
        $clone = clone $this;

        $clone->name = $name;

        return $clone;
    }

    public function url(URL|null $url): self
    {
        $clone = clone $this;

        $clone->url = $url;

        return $clone;
    }

    public function email(Email|null $email): self
    {
        $clone = clone $this;

        $clone->email = $email;

        return $clone;
    }

    protected function toArray(): array
    {
        return Arr::filter([
            'name' => $this->name,
            'url' => $this->url,
            'email' => $this->email,
        ]);
    }
}
