<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Components;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\CallbackFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\ParameterFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\RequestBodyFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\ResponseFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\SchemaFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\SecuritySchemeFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\SharedFields\Examples\ExampleEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\SharedFields\Examples\Examples;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\SharedFields\Headers\HeaderEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\SharedFields\Headers\Headers;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\SharedFields\Links\LinkEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\SharedFields\Links\Links;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Arr;

final class Components extends ExtensibleObject
{
    /** @var SchemaFactory[]|null */
    private array|null $schemas = null;

    /** @var ResponseFactory[]|null */
    private array|null $responses = null;

    /** @var ParameterFactory[]|null */
    private array|null $parameters = null;
    private Examples|null $examples = null;

    /** @var RequestBodyFactory[]|null */
    private array|null $requestBodies = null;

    private Headers|null $headers = null;

    /** @var SecuritySchemeFactory[]|null */
    private array|null $securitySchemes = null;

    private Links|null $links = null;

    /** @var CallbackFactory[]|null */
    private array|null $callbackFactories = null;

    public function schemas(SchemaFactory ...$schema): self
    {
        $clone = clone $this;

        $clone->schemas = [] !== $schema ? $schema : null;

        return $clone;
    }

    public function responses(ResponseFactory ...$reusableResponseFactory): self
    {
        $clone = clone $this;

        $clone->responses = $reusableResponseFactory;

        return $clone;
    }

    public function parameters(ParameterFactory ...$reusableParameterFactory): self
    {
        $clone = clone $this;

        $clone->parameters = [] !== $reusableParameterFactory ? $reusableParameterFactory : null;

        return $clone;
    }

    public function examples(ExampleEntry ...$exampleEntry): self
    {
        $clone = clone $this;

        $clone->examples = Examples::create(...$exampleEntry);

        return $clone;
    }

    public static function create(): self
    {
        return new self();
    }

    public function requestBodies(RequestBodyFactory ...$reusableRequestBodyFactory): self
    {
        $clone = clone $this;

        $clone->requestBodies = [] !== $reusableRequestBodyFactory ? $reusableRequestBodyFactory : null;

        return $clone;
    }

    public function headers(HeaderEntry ...$headerEntry): self
    {
        $clone = clone $this;

        $clone->headers = Headers::create(...$headerEntry);

        return $clone;
    }

    public function securitySchemes(SecuritySchemeFactory ...$securitySchemeFactory): self
    {
        $clone = clone $this;

        $clone->securitySchemes = [] !== $securitySchemeFactory ? $securitySchemeFactory : null;

        return $clone;
    }

    public function links(LinkEntry ...$linkEntry): self
    {
        $clone = clone $this;

        $clone->links = Links::create(...$linkEntry);

        return $clone;
    }

    public function callbacks(CallbackFactory ...$reusableCallbackFactory): self
    {
        $clone = clone $this;

        $clone->callbackFactories = [] !== $reusableCallbackFactory ? $reusableCallbackFactory : null;

        return $clone;
    }

    protected function toArray(): array
    {
        $schemas = [];
        foreach ($this->schemas ?? [] as $schema) {
            $schemas[$schema::name()] = $schema->component();
        }

        $responses = [];
        foreach ($this->responses ?? [] as $response) {
            $responses[$response::name()] = $response->component();
        }

        $parameters = [];
        foreach ($this->parameters ?? [] as $parameter) {
            $parameters[$parameter::name()] = $parameter->component();
        }

        $requestBodies = [];
        foreach ($this->requestBodies ?? [] as $requestBody) {
            $requestBodies[$requestBody::name()] = $requestBody->component();
        }

        $securitySchemes = [];
        foreach ($this->securitySchemes ?? [] as $securityScheme) {
            $securitySchemes[$securityScheme::name()] = $securityScheme->component();
        }

        $callbacks = [];
        foreach ($this->callbackFactories ?? [] as $factory) {
            $callback = $factory->component();
            $callbacks[$factory::name()] = $callback;
        }

        return Arr::filter([
            'schemas' => [] !== $schemas ? $schemas : null,
            'responses' => [] !== $responses ? $responses : null,
            'parameters' => [] !== $parameters ? $parameters : null,
            'examples' => $this->examples,
            'requestBodies' => [] !== $requestBodies ? $requestBodies : null,
            'headers' => $this->headers,
            'securitySchemes' => [] !== $securitySchemes ? $securitySchemes : null,
            'links' => $this->links,
            'callbacks' => [] !== $callbacks ? $callbacks : null,
            // TODO: add extensions
        ]);
    }
}
