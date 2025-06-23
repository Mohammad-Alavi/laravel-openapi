<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Server;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Server\Fields\Description;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Server\Fields\URL;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Server\Fields\Variables\VariableEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Server\Fields\Variables\Variables;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Arr;

final class Server extends ExtensibleObject
{
    private Description|null $description = null;
    private Variables|null $variables = null;

    private function __construct(
        private URL $url,
    ) {
    }

    public static function default(): self
    {
        return new self(URL::create('/'));
    }

    public static function create(URL $url): self
    {
        return new self($url);
    }

    public function url(URL $url): self
    {
        $clone = clone $this;

        $clone->url = $url;

        return $clone;
    }

    public function description(Description|null $description): self
    {
        $clone = clone $this;

        $clone->description = $description;

        return $clone;
    }

    public function variables(VariableEntry ...$variableEntry): self
    {
        $clone = clone $this;

        $clone->variables = Variables::create(...$variableEntry);

        return $clone;
    }

    protected function toArray(): array
    {
        return Arr::filter([
            'url' => $this->url,
            'description' => $this->description,
            'variables' => $this->variables,
        ]);
    }
}
