<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Components;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\CallbackFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\ParameterFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\RequestBodyFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\ResponseFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\SchemaFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\SecuritySchemeFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Callback\Callback;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Parameter;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\RequestBody\RequestBody;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response\Response;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\SecurityScheme;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Arr;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Examples\ExampleEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Examples\Examples;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Headers\HeaderEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Headers\Headers;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Links\LinkEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Links\Links;

final class Components extends ExtensibleObject
{
    /** @var Schema[]|null */
    private array|null $schemas = null;

    /** @var Response[]|null */
    private array|null $responses = null;

    /** @var Parameter[]|null */
    private array|null $parameters = null;

    private Examples|null $examples = null;

    /** @var RequestBody[]|null */
    private array|null $requestBodies = null;

    private Headers|null $headers = null;

    /** @var SecurityScheme[]|null */
    private array|null $securitySchemes = null;

    private Links|null $links = null;

    /** @var Callback[]|null */
    private array|null $callbacks = null;

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

    public function requestBodies(RequestBodyFactory ...$requestBodyFactory): self
    {
        $clone = clone $this;

        foreach ($requestBodyFactory as $factory) {
            $clone->requestBodies[$factory::name()] = $factory->component();
        }

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

        foreach ($securitySchemeFactory as $factory) {
            $clone->securitySchemes[$factory::name()] = $factory->component();
        }

        return $clone;
    }

    public function links(LinkEntry ...$linkEntry): self
    {
        $clone = clone $this;

        $clone->links = Links::create(...$linkEntry);

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

    protected function toArray(): array
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
            // TODO: add extensions
        ]);
    }
}
