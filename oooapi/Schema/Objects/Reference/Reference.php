<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Reference;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\NonExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Reference\Fields\Ref;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Arr;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Description;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Summary;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Validator;

final class Reference extends NonExtensibleObject
{
    private Summary|null $summary = null;
    private Description|null $description = null;

    private function __construct(
        private readonly Ref $ref,
    ) {
    }

    public function description(string $description): self
    {
        $clone = clone $this;

        $clone->description = Description::create($description);

        return $clone;
    }

    public static function create(string $ref): self
    {
        return new self(Ref::create($ref));
    }

    /**
     * Create a reference to a schema in components.
     *
     * @param string $name The schema name in #/components/schemas/
     */
    public static function schema(string $name): self
    {
        Validator::componentName($name);

        return new self(Ref::create("#/components/schemas/{$name}"));
    }

    /**
     * Create a reference to a response in components.
     *
     * @param string $name The response name in #/components/responses/
     */
    public static function response(string $name): self
    {
        Validator::componentName($name);

        return new self(Ref::create("#/components/responses/{$name}"));
    }

    /**
     * Create a reference to a parameter in components.
     *
     * @param string $name The parameter name in #/components/parameters/
     */
    public static function parameter(string $name): self
    {
        Validator::componentName($name);

        return new self(Ref::create("#/components/parameters/{$name}"));
    }

    /**
     * Create a reference to a request body in components.
     *
     * @param string $name The request body name in #/components/requestBodies/
     */
    public static function requestBody(string $name): self
    {
        Validator::componentName($name);

        return new self(Ref::create("#/components/requestBodies/{$name}"));
    }

    /**
     * Create a reference to a header in components.
     *
     * @param string $name The header name in #/components/headers/
     */
    public static function header(string $name): self
    {
        Validator::componentName($name);

        return new self(Ref::create("#/components/headers/{$name}"));
    }

    /**
     * Create a reference to a security scheme in components.
     *
     * @param string $name The security scheme name in #/components/securitySchemes/
     */
    public static function securityScheme(string $name): self
    {
        Validator::componentName($name);

        return new self(Ref::create("#/components/securitySchemes/{$name}"));
    }

    /**
     * Create a reference to a link in components.
     *
     * @param string $name The link name in #/components/links/
     */
    public static function link(string $name): self
    {
        Validator::componentName($name);

        return new self(Ref::create("#/components/links/{$name}"));
    }

    /**
     * Create a reference to a callback in components.
     *
     * @param string $name The callback name in #/components/callbacks/
     */
    public static function callback(string $name): self
    {
        Validator::componentName($name);

        return new self(Ref::create("#/components/callbacks/{$name}"));
    }

    /**
     * Create a reference to a path item in components.
     *
     * @param string $name The path item name in #/components/pathItems/
     */
    public static function pathItem(string $name): self
    {
        Validator::componentName($name);

        return new self(Ref::create("#/components/pathItems/{$name}"));
    }

    /**
     * Create a reference to an example in components.
     *
     * @param string $name The example name in #/components/examples/
     */
    public static function example(string $name): self
    {
        Validator::componentName($name);

        return new self(Ref::create("#/components/examples/{$name}"));
    }

    public function summary(string $summary): self
    {
        $clone = clone $this;

        $clone->summary = Summary::create($summary);

        return $clone;
    }

    public function toArray(): array
    {
        return Arr::filter([
            '$ref' => $this->ref,
            'summary' => $this->summary,
            'description' => $this->description,
        ]);
    }
}
