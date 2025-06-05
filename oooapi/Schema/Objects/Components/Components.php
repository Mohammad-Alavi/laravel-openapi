<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Components;

use MohammadAlavi\LaravelOpenApi\Contracts\Abstract\Factories\Components\ReusableCallbackFactory;
use MohammadAlavi\LaravelOpenApi\Contracts\Abstract\Factories\Components\ReusableParameterFactory;
use MohammadAlavi\LaravelOpenApi\Contracts\Abstract\Factories\Components\ReusableRequestBodyFactory;
use MohammadAlavi\LaravelOpenApi\Contracts\Abstract\Factories\Components\ReusableResponseFactory;
use MohammadAlavi\LaravelOpenApi\Contracts\Abstract\Factories\Components\ReusableSchemaFactory;
use MohammadAlavi\LaravelOpenApi\Contracts\Abstract\Factories\Components\SecuritySchemeFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\SimpleCreator;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Example;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Header\Header;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Link;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\SchemaComposition;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\SimpleCreatorTrait;
use MohammadAlavi\ObjectOrientedOpenAPI\Utilities\Arr;

class Components extends ExtensibleObject implements SimpleCreator
{
    use SimpleCreatorTrait;

    /** @var ReusableSchemaFactory[]|SchemaComposition[]|null */
    protected array|null $schemas = null;

    /** @var ReusableResponseFactory[]|null */
    protected array|null $responses = null;

    /** @var ReusableParameterFactory[]|null */
    protected array|null $parameters = null;

    /** @var Example[]|null */
    protected array|null $examples = null;

    /** @var ReusableRequestBodyFactory[]|null */
    protected array|null $requestBodies = null;

    /** @var Header[]|null */
    protected array|null $headers = null;

    /** @var SecuritySchemeFactory[]|null */
    protected array|null $securitySchemes = null;

    /** @var Link[]|null */
    protected array|null $links = null;

    /** @var ReusableCallbackFactory[]|null */
    protected array|null $callbackFactories = null;

    public function schemas(ReusableSchemaFactory|SchemaComposition ...$schema): static
    {
        $clone = clone $this;

        $clone->schemas = [] !== $schema ? $schema : null;

        return $clone;
    }

    public function responses(ReusableResponseFactory ...$reusableResponseFactory): static
    {
        $clone = clone $this;

        $clone->responses = $reusableResponseFactory;

        return $clone;
    }

    public function parameters(ReusableParameterFactory ...$reusableParameterFactory): static
    {
        $clone = clone $this;

        $clone->parameters = [] !== $reusableParameterFactory ? $reusableParameterFactory : null;

        return $clone;
    }

    public function examples(Example ...$example): static
    {
        $clone = clone $this;

        $clone->examples = [] !== $example ? $example : null;

        return $clone;
    }

    public function requestBodies(ReusableRequestBodyFactory ...$reusableRequestBodyFactory): static
    {
        $clone = clone $this;

        $clone->requestBodies = [] !== $reusableRequestBodyFactory ? $reusableRequestBodyFactory : null;

        return $clone;
    }

    public function headers(Header ...$header): static
    {
        $clone = clone $this;

        $clone->headers = [] !== $header ? $header : null;

        return $clone;
    }

    public function securitySchemes(SecuritySchemeFactory ...$securitySchemeFactory): static
    {
        $clone = clone $this;

        $clone->securitySchemes = [] !== $securitySchemeFactory ? $securitySchemeFactory : null;

        return $clone;
    }

    public function links(Link ...$link): static
    {
        $clone = clone $this;

        $clone->links = [] !== $link ? $link : null;

        return $clone;
    }

    public function callbacks(ReusableCallbackFactory ...$reusableCallbackFactory): static
    {
        $clone = clone $this;

        $clone->callbackFactories = [] !== $reusableCallbackFactory ? $reusableCallbackFactory : null;

        return $clone;
    }

    protected function toArray(): array
    {
        $schemas = [];
        foreach ($this->schemas ?? [] as $schema) {
            if ($schema instanceof SchemaComposition) {
                $schemas[$schema->key()] = $schema;
                continue;
            }

            $schemas[$schema::key()] = $schema->build();
        }

        $responses = [];
        foreach ($this->responses ?? [] as $response) {
            $responses[$response::key()] = $response->build();
        }

        $parameters = [];
        foreach ($this->parameters ?? [] as $parameter) {
            $parameters[$parameter::key()] = $parameter->build();
        }

        $examples = [];
        foreach ($this->examples ?? [] as $example) {
            $examples[$example->key()] = $example;
        }

        $requestBodies = [];
        foreach ($this->requestBodies ?? [] as $requestBody) {
            $requestBodies[$requestBody::key()] = $requestBody->build();
        }

        $headers = [];
        foreach ($this->headers ?? [] as $header) {
            $headers[$header->key()] = $header;
        }

        $securitySchemes = [];
        foreach ($this->securitySchemes ?? [] as $securityScheme) {
            $securitySchemes[$securityScheme::key()] = $securityScheme->build();
        }

        $links = [];
        foreach ($this->links ?? [] as $link) {
            $links[$link->key()] = $link;
        }

        $callbacks = [];
        foreach ($this->callbackFactories ?? [] as $factory) {
            $callback = $factory->build();
            $callbacks[$factory::key()] = $callback;
        }

        return Arr::filter([
            'schemas' => [] !== $schemas ? $schemas : null,
            'responses' => [] !== $responses ? $responses : null,
            'parameters' => [] !== $parameters ? $parameters : null,
            'examples' => [] !== $examples ? $examples : null,
            'requestBodies' => [] !== $requestBodies ? $requestBodies : null,
            'headers' => [] !== $headers ? $headers : null,
            'securitySchemes' => [] !== $securitySchemes ? $securitySchemes : null,
            'links' => [] !== $links ? $links : null,
            'callbacks' => [] !== $callbacks ? $callbacks : null,
            // TODO: add extensions
        ]);
    }
}
