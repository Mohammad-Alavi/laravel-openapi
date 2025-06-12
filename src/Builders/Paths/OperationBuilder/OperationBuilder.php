<?php

namespace MohammadAlavi\LaravelOpenApi\Builders\Paths\OperationBuilder;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use MohammadAlavi\LaravelOpenApi\Attributes\RequestBody;
use MohammadAlavi\LaravelOpenApi\Attributes\Responses;
use MohammadAlavi\LaravelOpenApi\Builders\ExtensionBuilder;
use MohammadAlavi\LaravelOpenApi\Builders\Paths\OperationBuilder\Builders\CallbackBuilder;
use MohammadAlavi\LaravelOpenApi\Builders\Paths\OperationBuilder\Builders\ParametersBuilder;
use MohammadAlavi\LaravelOpenApi\Builders\Paths\OperationBuilder\Builders\RequestBodyBuilder;
use MohammadAlavi\LaravelOpenApi\Builders\Paths\OperationBuilder\Builders\ResponsesBuilder;
use MohammadAlavi\LaravelOpenApi\Builders\Paths\OperationBuilder\Builders\SecurityBuilder;
use MohammadAlavi\LaravelOpenApi\Builders\Paths\OperationBuilder\Builders\ServerBuilder;
use MohammadAlavi\LaravelOpenApi\Builders\Paths\OperationBuilder\Builders\TagBuilder;
use MohammadAlavi\LaravelOpenApi\Objects\RouteInfo;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Operation;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\Security;

final readonly class OperationBuilder
{
    public function __construct(
        private CallbackBuilder $callbackBuilder,
        private ParametersBuilder $parametersBuilder,
        private RequestBodyBuilder $requestBodyBuilder,
        private ResponsesBuilder $responsesBuilder,
        private SecurityBuilder $securityBuilder,
        private ServerBuilder $serverBuilder,
        private TagBuilder $tagBuilder,
        private ExtensionBuilder $extensionBuilder,
    ) {
    }

    // TODO: maybe we can abstract the usage of RouteInformation everywhere and use an interface instead
    public function build(RouteInfo $routeInfo): Operation
    {
        $operation = $routeInfo->operationAttribute();

        $operationId = $operation?->id;
        $tags = $this->tagBuilder->build(Arr::wrap($operation?->tags));
        $security = null !== $operation?->security && '' !== $operation?->security && '0' !== $operation?->security ? $this->securityBuilder->build($operation?->security) : null;
        $method = $operation?->method ?? Str::lower($routeInfo->method());
        $summary = $operation?->summary;
        $description = $operation?->description;
        $deprecated = $operation?->deprecated;
        $servers = $this->serverBuilder->build(Arr::wrap($operation?->servers));
        $parameterCollection = $this->parametersBuilder->build($routeInfo);
        $requestBody = $routeInfo->requestBodyAttribute() instanceof RequestBody
            ? $this->requestBodyBuilder->build($routeInfo->requestBodyAttribute())
            : null;
        $responses = $routeInfo->responsesAttribute() instanceof Responses
            ? $this->responsesBuilder->build($routeInfo->responsesAttribute())
            : null;
        $callbacks = $this->callbackBuilder->build($routeInfo);

        $operation = Operation::create()
            ->action($method)
            ->tags(...$tags)
            ->summary($summary)
            ->description($description)
            ->operationId($operationId)
            ->deprecated($deprecated)
            ->parameters($parameterCollection)
            ->requestBody($requestBody)
            ->responses($responses)
            ->callbacks(...$callbacks)
            ->servers(...$servers);
        if ($security instanceof Security) {
            $operation = $operation->security($security);
        }

        $this->extensionBuilder->build($operation, $routeInfo->extensionAttributes());

        return $operation;
    }
}
