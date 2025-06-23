<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Components;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\CallbackFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\ParameterFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\RequestBodyFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\ResponseFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\SchemaFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\SecuritySchemeFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Components\Fields\Links\LinkEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Components\Fields\Links\Links;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Example;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Header\Header;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Arr;

final class Components extends ExtensibleObject
{
    /** @var SchemaFactory[]|null */
    private array|null $schemas = null;

    /** @var ResponseFactory[]|null */
    private array|null $responses = null;

    /** @var ParameterFactory[]|null */
    private array|null $parameters = null;

    /** @var Example[]|null */
    private array|null $examples = null;

    /** @var RequestBodyFactory[]|null */
    private array|null $requestBodies = null;

    /** @var Header[]|null */
    private array|null $headers = null;

    /** @var SecuritySchemeFactory[]|null */
    private array|null $securitySchemes = null;

    private Links|null $links = null;

    /** @var CallbackFactory[]|null */
    private array|null $callbackFactories = null;

    private function __construct()
    {
    }

    public static function create(): self
    {
        return new self();
    }

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

    public function examples(Example ...$example): self
    {
        $clone = clone $this;

        $clone->examples = [] !== $example ? $example : null;

        return $clone;
    }

    public function requestBodies(RequestBodyFactory ...$reusableRequestBodyFactory): self
    {
        $clone = clone $this;

        $clone->requestBodies = [] !== $reusableRequestBodyFactory ? $reusableRequestBodyFactory : null;

        return $clone;
    }

    public function headers(Header ...$header): self
    {
        $clone = clone $this;

        $clone->headers = [] !== $header ? $header : null;

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

        $examples = [];
        foreach ($this->examples ?? [] as $example) {
            $examples[$example->key()] = $example;
        }

        $requestBodies = [];
        foreach ($this->requestBodies ?? [] as $requestBody) {
            $requestBodies[$requestBody::name()] = $requestBody->component();
        }

        $headers = [];
        foreach ($this->headers ?? [] as $header) {
            $headers[$header->key()] = $header;
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
            'examples' => [] !== $examples ? $examples : null,
            'requestBodies' => [] !== $requestBodies ? $requestBodies : null,
            'headers' => [] !== $headers ? $headers : null,
            'securitySchemes' => [] !== $securitySchemes ? $securitySchemes : null,
            'links' => $this->links,
            'callbacks' => [] !== $callbacks ? $callbacks : null,
            // TODO: add extensions
        ]);
    }
}
