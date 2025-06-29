<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Server;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Server\Fields\URL;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Server\Fields\Variables\VariableEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Server\Fields\Variables\Variables;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Arr;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Description;

final class Server extends ExtensibleObject
{
    private Description|null $description = null;
    private Variables|null $variables = null;

    private function __construct(
        private readonly URL $url,
    ) {
    }

    public static function default(): self
    {
        return self::create('/');
    }

    public static function create(string $url): self
    {
        return new self(URL::create($url));
    }

    public function description(string $description): self
    {
        $clone = clone $this;

        $clone->description = Description::create($description);

        return $clone;
    }

    public function variables(VariableEntry ...$variableEntry): self
    {
        $clone = clone $this;

        $clone->variables = Variables::create(...$variableEntry);

        return $clone;
    }

    public function toArray(): array
    {
        return Arr::filter([
            'url' => $this->url,
            'description' => $this->description,
            'variables' => $this->variables,
        ]);
    }
}
