<?php

namespace MohammadAlavi\ObjectOrientedOAS\Objects;

use MohammadAlavi\ObjectOrientedOAS\Contracts\SchemaContract;
use MohammadAlavi\ObjectOrientedOAS\Utilities\Arr;

/**
 * @property \MohammadAlavi\ObjectOrientedOAS\Objects\Schema[]|null $schemas
 * @property \MohammadAlavi\ObjectOrientedOAS\Objects\Response[]|null $responses
 * @property \MohammadAlavi\ObjectOrientedOAS\Objects\Parameter[]|null $parameters
 * @property \MohammadAlavi\ObjectOrientedOAS\Objects\Example[]|null $examples
 * @property \MohammadAlavi\ObjectOrientedOAS\Objects\RequestBody[]|null $requestBodies
 * @property \MohammadAlavi\ObjectOrientedOAS\Objects\Header[]|null $headers
 * @property \MohammadAlavi\ObjectOrientedOAS\Objects\SecurityScheme[]|null $securitySchemes
 * @property \MohammadAlavi\ObjectOrientedOAS\Objects\Link[]|null $links
 */
class Components extends BaseObject
{
    /**
     * @var \MohammadAlavi\ObjectOrientedOAS\Objects\Schema[]|null
     */
    protected $schemas;

    /**
     * @var \MohammadAlavi\ObjectOrientedOAS\Objects\Response[]|null
     */
    protected $responses;

    /**
     * @var \MohammadAlavi\ObjectOrientedOAS\Objects\Parameter[]|null
     */
    protected $parameters;

    /**
     * @var \MohammadAlavi\ObjectOrientedOAS\Objects\Example[]|null
     */
    protected $examples;

    /**
     * @var \MohammadAlavi\ObjectOrientedOAS\Objects\RequestBody[]|null
     */
    protected $requestBodies;

    /**
     * @var \MohammadAlavi\ObjectOrientedOAS\Objects\Header[]|null
     */
    protected $headers;

    /**
     * @var \MohammadAlavi\ObjectOrientedOAS\Objects\SecurityScheme[]|null
     */
    protected $securitySchemes;

    /**
     * @var \MohammadAlavi\ObjectOrientedOAS\Objects\Link[]|null
     */
    protected $links;

    /**
     * @var \MohammadAlavi\ObjectOrientedOAS\Objects\PathItem[]|null
     */
    protected $callbacks;

    /**
     * @param \MohammadAlavi\ObjectOrientedOAS\Contracts\SchemaContract[] $schemas
     * @return static
     */
    public function schemas(SchemaContract ...$schemas): self
    {
        $instance = clone $this;

        $instance->schemas = $schemas ?: null;

        return $instance;
    }

    /**
     * @param \MohammadAlavi\ObjectOrientedOAS\Objects\Response[] $responses
     * @return static
     */
    public function responses(Response ...$responses): self
    {
        $instance = clone $this;

        $instance->responses = $responses ?: null;

        return $instance;
    }

    /**
     * @param \MohammadAlavi\ObjectOrientedOAS\Objects\Parameter[] $parameters
     * @return static
     */
    public function parameters(Parameter ...$parameters): self
    {
        $instance = clone $this;

        $instance->parameters = $parameters ?: null;

        return $instance;
    }

    /**
     * @param \MohammadAlavi\ObjectOrientedOAS\Objects\Example[] $examples
     * @return static
     */
    public function examples(Example ...$examples): self
    {
        $instance = clone $this;

        $instance->examples = $examples ?: null;

        return $instance;
    }

    /**
     * @param \MohammadAlavi\ObjectOrientedOAS\Objects\RequestBody[] $requestBodies
     * @return static
     */
    public function requestBodies(RequestBody ...$requestBodies): self
    {
        $instance = clone $this;

        $instance->requestBodies = $requestBodies ?: null;

        return $instance;
    }

    /**
     * @param \MohammadAlavi\ObjectOrientedOAS\Objects\Header[] $headers
     * @return static
     */
    public function headers(Header ...$headers): self
    {
        $instance = clone $this;

        $instance->headers = $headers ?: null;

        return $instance;
    }

    /**
     * @param \MohammadAlavi\ObjectOrientedOAS\Objects\SecurityScheme[] $securitySchemes
     * @return static
     */
    public function securitySchemes(SecurityScheme ...$securitySchemes): self
    {
        $instance = clone $this;

        $instance->securitySchemes = $securitySchemes ?: null;

        return $instance;
    }

    /**
     * @param \MohammadAlavi\ObjectOrientedOAS\Objects\Link[] $links
     * @return static
     */
    public function links(Link ...$links): self
    {
        $instance = clone $this;

        $instance->links = $links ?: null;

        return $instance;
    }

    /**
     * @param \MohammadAlavi\ObjectOrientedOAS\Objects\PathItem[] $callbacks
     * @return static
     */
    public function callbacks(PathItem ...$callbacks): self
    {
        $instance = clone $this;

        $instance->callbacks = $callbacks ?: null;

        return $instance;
    }

    /**
     * @return array
     */
    protected function generate(): array
    {
        $schemas = [];
        foreach ($this->schemas ?? [] as $schema) {
            $schemas[$schema->objectId] = $schema;
        }

        $responses = [];
        foreach ($this->responses ?? [] as $response) {
            $responses[$response->objectId] = $response;
        }

        $parameters = [];
        foreach ($this->parameters ?? [] as $parameter) {
            $parameters[$parameter->objectId] = $parameter;
        }

        $examples = [];
        foreach ($this->examples ?? [] as $example) {
            $examples[$example->objectId] = $example;
        }

        $requestBodies = [];
        foreach ($this->requestBodies ?? [] as $requestBodie) {
            $requestBodies[$requestBodie->objectId] = $requestBodie;
        }

        $headers = [];
        foreach ($this->headers ?? [] as $header) {
            $headers[$header->objectId] = $header;
        }

        $securitySchemes = [];
        foreach ($this->securitySchemes ?? [] as $securityScheme) {
            $securitySchemes[$securityScheme->objectId] = $securityScheme;
        }

        $links = [];
        foreach ($this->links ?? [] as $link) {
            $links[$link->objectId] = $link;
        }

        $callbacks = [];
        foreach ($this->callbacks ?? [] as $callback) {
            $callbacks[$callback->objectId][$callback->route] = $callback;
        }

        return Arr::filter([
            'schemas' => $schemas ?: null,
            'responses' => $responses ?: null,
            'parameters' => $parameters ?: null,
            'examples' => $examples ?: null,
            'requestBodies' => $requestBodies ?: null,
            'headers' => $headers ?: null,
            'securitySchemes' => $securitySchemes ?: null,
            'links' => $links ?: null,
            'callbacks' => $callbacks ?: null,
        ]);
    }
}
