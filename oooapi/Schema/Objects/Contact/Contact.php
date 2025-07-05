<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Contact;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Contact\Fields\Email;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Arr;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Name;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\URL;

final class Contact extends ExtensibleObject
{
    private Name|null $name = null;
    private URL|null $url = null;
    private Email|null $email = null;

    public function name(string $name): self
    {
        $clone = clone $this;

        $clone->name = Name::create($name);

        return $clone;
    }

    public static function create(): self
    {
        return new self();
    }

    public function url(string $url): self
    {
        $clone = clone $this;

        $clone->url = URL::create($url);

        return $clone;
    }

    public function email(string $email): self
    {
        $clone = clone $this;

        $clone->email = Email::create($email);

        return $clone;
    }

    public function toArray(): array
    {
        return Arr::filter([
            'name' => $this->name,
            'url' => $this->url,
            'email' => $this->email,
        ]);
    }
}
