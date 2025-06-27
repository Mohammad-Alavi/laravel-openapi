<?php

namespace MohammadAlavi\LaravelOpenApi\Builders\Paths\OperationBuilder;

use Illuminate\Support\Arr;
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
use MohammadAlavi\LaravelOpenApi\Support\RouteInfo;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Operation\Fields\Description;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Operation\Fields\OperationId;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Operation\Fields\Summary;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Operation\Operation;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem\Support\AvailableOperation;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem\Support\HttpMethod;

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
    public function build(RouteInfo $routeInfo): AvailableOperation
    {
        $servers = null;
        $operation = Operation::create();
        $operationAttr = $routeInfo->operationAttribute();

        if (!is_null($operationAttr)) {
            $operation = $operation->tags(...$this->tagBuilder->build(Arr::wrap($operationAttr->tags)));
            if (!is_null($operationAttr->summary)) {
                $operation = $operation->summary(Summary::create($operationAttr->summary));
            }
            if (!is_null($operationAttr->description)) {
                $operation = $operation->description(Description::create($operationAttr->description));
            }
            if (!is_null($operationAttr->operationId)) {
                $operation = $operation->operationId(OperationId::create($operationAttr->operationId));
            }
            if (!blank($operationAttr->security)) {
                $operation = $operation->security($this->securityBuilder->build($operationAttr->security));
            }
            if (true === $operationAttr->deprecated) {
                $operation = $operation->deprecated();
            }
            $servers = $this->serverBuilder->build(Arr::wrap($operationAttr->servers));
        }
        $parameters = $this->parametersBuilder->build($routeInfo);
        $requestBody = $routeInfo->requestBodyAttribute() instanceof RequestBody
            ? $this->requestBodyBuilder->build($routeInfo->requestBodyAttribute())
            : null;
        $responses = $routeInfo->responsesAttribute() instanceof Responses
            ? $this->responsesBuilder->build($routeInfo->responsesAttribute())
            : null;
        $callbacks = $this->callbackBuilder->build($routeInfo->callbackAttributes());

        $operation = $operation->servers(...$servers)
            ->parameters($parameters)
            ->requestBody($requestBody)
            ->responses($responses)
            ->callbacks(...$callbacks);

        $this->extensionBuilder->build($operation, $routeInfo->extensionAttributes());

        return AvailableOperation::create(
            HttpMethod::from($routeInfo->method()),
            $operation,
        );
    }
}
