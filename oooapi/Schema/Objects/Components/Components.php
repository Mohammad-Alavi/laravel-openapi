<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Components;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\JSONSchema;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\CallbackFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\ExampleFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\HeaderFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\LinkFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\ParameterFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\PathItemFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\RequestBodyFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\ResponseFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\SchemaFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\SecuritySchemeFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Callback\Callback;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Example\Example;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Header\Header;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Link\Link;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Parameter;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem\PathItem;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\RequestBody\RequestBody;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response\Response;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\SecurityScheme;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Arr;

final class Components extends ExtensibleObject
{
    /** @var array<string, JSONSchema>|null */
    private array|null $schemas = null;

    /** @var array<string, Response>|null */
    private array|null $responses = null;

    /** @var array<string, Parameter>|null */
    private array|null $parameters = null;

    /** @var array<string, Example>|null */
    private array|null $examples = null;

    /** @var array<string, RequestBody>|null */
    private array|null $requestBodies = null;

    /** @var array<string, Header>|null */
    private array|null $headers = null;

    /** @var array<string, Link>|null */
    private array|null $links = null;

    /** @var array<string, SecurityScheme>|null */
    private array|null $securitySchemes = null;

    /** @var array<string, Callback>|null */
    private array|null $callbacks = null;

    /** @var array<string, PathItem>|null */
    private array|null $pathItems = null;

    public static function create(): self
    {
        return new self();
    }

    public function schemas(SchemaFactory ...$schemaFactory): self
    {
        $clone = clone $this;

        foreach ($schemaFactory as $factory) {
            $clone->schemas[$factory::name()] = $factory->component();
        }

        return $clone;
    }

    public function responses(ResponseFactory ...$responseFactory): self
    {
        $clone = clone $this;

        foreach ($responseFactory as $factory) {
            $clone->responses[$factory::name()] = $factory->component();
        }

        return $clone;
    }

    public function parameters(ParameterFactory ...$parameterFactory): self
    {
        $clone = clone $this;

        foreach ($parameterFactory as $factory) {
            $clone->parameters[$factory::name()] = $factory->component();
        }

        return $clone;
    }

    public function examples(ExampleFactory ...$exampleFactory): self
    {
        $clone = clone $this;

        foreach ($exampleFactory as $factory) {
            $clone->examples[$factory::name()] = $factory->component();
        }

        return $clone;
    }

    public function requestBodies(RequestBodyFactory ...$requestBodyFactory): self
    {
        $clone = clone $this;

        foreach ($requestBodyFactory as $factory) {
            $clone->requestBodies[$factory::name()] = $factory->component();
        }

        return $clone;
    }

    public function headers(HeaderFactory ...$headerFactory): self
    {
        $clone = clone $this;

        foreach ($headerFactory as $factory) {
            $clone->headers[$factory::name()] = $factory->component();
        }

        return $clone;
    }

    public function securitySchemes(SecuritySchemeFactory ...$securitySchemeFactory): self
    {
        $clone = clone $this;

        foreach ($securitySchemeFactory as $factory) {
            $clone->securitySchemes[$factory::name()] = $factory->component();
        }

        return $clone;
    }

    public function links(LinkFactory ...$linkFactory): self
    {
        $clone = clone $this;

        foreach ($linkFactory as $factory) {
            $clone->links[$factory::name()] = $factory->component();
        }

        return $clone;
    }

    public function callbacks(CallbackFactory ...$callbackFactory): self
    {
        $clone = clone $this;

        foreach ($callbackFactory as $factory) {
            $clone->callbacks[$factory::name()] = $factory->component();
        }

        return $clone;
    }

    public function pathItems(PathItemFactory ...$pathItemFactory): self
    {
        $clone = clone $this;

        foreach ($pathItemFactory as $factory) {
            $clone->pathItems[$factory::name()] = $factory->component();
        }

        return $clone;
    }

    public function toArray(): array
    {
        return Arr::filter([
            'schemas' => $this->schemas,
            'responses' => $this->responses,
            'parameters' => $this->parameters,
            'examples' => $this->examples,
            'requestBodies' => $this->requestBodies,
            'headers' => $this->headers,
            'securitySchemes' => $this->securitySchemes,
            'links' => $this->links,
            'callbacks' => $this->callbacks,
            'pathItems' => $this->pathItems,
        ]);
    }
}
