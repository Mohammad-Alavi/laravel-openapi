<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Link;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Link\Fields\OperationId;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Link\Fields\OperationRef;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Server\Server;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Arr;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\RuntimeExpression\RuntimeExpressionAbstract;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Description;
use Webmozart\Assert\Assert;

/**
 * Link Object.
 *
 * Represents a possible design-time link for a response. The presence of a
 * link does not guarantee the caller's ability to invoke it. Either
 * operationRef or operationId can be used, but not both.
 *
 * @see https://spec.openapis.org/oas/v3.1.0#link-object
 */
final class Link extends ExtensibleObject
{
    private OperationRef|null $operationRef = null;
    private OperationId|null $operationId = null;

    /** @var array<string, mixed>|null Map of parameter names to values or expressions */
    private array|null $parameters = null;

    /** @var mixed A literal value or expression to use as a request body */
    private mixed $requestBody = null;

    private Description|null $description = null;
    private Server|null $server = null;

    public function operationRef(string $operationRef): self
    {
        Assert::null(
            $this->operationId,
            'operationRef and operationId fields are mutually exclusive.',
        );

        $clone = clone $this;

        $clone->operationRef = OperationRef::create($operationRef);

        return $clone;
    }

    public static function create(): self
    {
        return new self();
    }

    public function operationId(string $operationId): self
    {
        Assert::null(
            $this->operationRef,
            'operationId and operationRef fields are mutually exclusive.',
        );

        $clone = clone $this;

        $clone->operationId = OperationId::create($operationId);

        return $clone;
    }

    /**
     * A map representing parameters to pass to an operation.
     *
     * The key is the parameter name to be used, whereas the value can be
     * a constant or a runtime expression to be evaluated and passed.
     *
     * @param array<string, mixed|RuntimeExpressionAbstract> $parameters
     */
    public function parameters(array $parameters): self
    {
        $clone = clone $this;

        $clone->parameters = when(blank($parameters), null, $this->convertExpressions($parameters));

        return $clone;
    }

    /**
     * Convert RuntimeExpressionAbstract instances to strings in the array.
     *
     * @param array<string, mixed|RuntimeExpressionAbstract> $parameters
     *
     * @return array<string, mixed>
     */
    private function convertExpressions(array $parameters): array
    {
        return array_map(
            static fn (mixed $value): mixed => $value instanceof RuntimeExpressionAbstract
                ? $value->value()
                : $value,
            $parameters,
        );
    }

    /**
     * A literal value or runtime expression to use as a request body when calling the target operation.
     *
     * @param mixed|RuntimeExpressionAbstract $requestBody
     */
    public function requestBody(mixed $requestBody): self
    {
        $clone = clone $this;

        $clone->requestBody = $requestBody instanceof RuntimeExpressionAbstract
            ? $requestBody->value()
            : $requestBody;

        return $clone;
    }

    public function description(string $description): self
    {
        $clone = clone $this;

        $clone->description = Description::create($description);

        return $clone;
    }

    public function server(Server $server): self
    {
        $clone = clone $this;

        $clone->server = $server;

        return $clone;
    }

    public function toArray(): array
    {
        return Arr::filter([
            'operationRef' => $this->operationRef,
            'operationId' => $this->operationId,
            'parameters' => $this->parameters,
            'requestBody' => $this->requestBody,
            'description' => $this->description,
            'server' => $this->server,
        ]);
    }
}
