<?php

namespace MohammadAlavi\LaravelOpenApi\Builders;

use MohammadAlavi\LaravelOpenApi\Attributes\RequestBody;
use MohammadAlavi\LaravelOpenApi\Attributes\Responses;
use MohammadAlavi\LaravelOpenApi\Support\RouteInfo;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Operation\Operation;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem\Support\AvailableOperation;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem\Support\HttpMethod;

final readonly class OperationBuilder
{
    public function __construct(
        private TagBuilder $tagBuilder,
        private ParametersBuilder $parametersBuilder,
        private RequestBodyBuilder $requestBodyBuilder,
        private ResponsesBuilder $responsesBuilder,
        private ExternalDocumentationBuilder $externalDocumentationBuilder,
        private CallbackBuilder $callbackBuilder,
        private SecurityBuilder $securityBuilder,
        private ServerBuilder $serverBuilder,
        private ExtensionBuilder $extensionBuilder,
    ) {
    }

    // TODO: maybe we can abstract the usage of RouteInformation everywhere and use an interface instead
    public function build(RouteInfo $routeInfo): AvailableOperation
    {
        $operation = Operation::create();
        $attribute = $routeInfo->operationAttribute();

        if (!is_null($attribute)) {
            if (!is_null($attribute->summary)) {
                $operation = $operation->summary($attribute->summary);
            }
            if (!is_null($attribute->description)) {
                $operation = $operation->description($attribute->description);
            }
            if (!is_null($attribute->operationId)) {
                $operation = $operation->operationId($attribute->operationId);
            }
            if (!is_null($attribute->parameters)) {
                $operation = $operation->parameters($this->parametersBuilder->build($routeInfo));
            }
            if (!is_null($attribute->requestBody)) {
                $operation = $operation->requestBody($this->requestBodyBuilder->build($attribute->requestBody));
            }
            if (!is_null($attribute->externalDocs)) {
                $operation = $operation->externalDocs(
                    $this->externalDocumentationBuilder->build($attribute->externalDocs),
                );
            }
            if (!blank($attribute->security)) {
                $operation = $operation->security($this->securityBuilder->build($attribute->security));
            }
            if (true === $attribute->deprecated) {
                $operation = $operation->deprecated();
            }
            $operation = $operation->servers(...$this->serverBuilder->build(...$attribute->getServers()));
            $operation = $operation->tags(...$this->tagBuilder->build(...$attribute->getTags()));
        }

        if ($routeInfo->responsesAttribute() instanceof Responses) {
            $operation = $operation->responses(
                $this->responsesBuilder->build($routeInfo->responsesAttribute()),
            );
        }

        $callbacks = $this->callbackBuilder->build($routeInfo->callbackAttributes());

        $operation = $operation->parameters($this->parametersBuilder->build($routeInfo))
            ->callbacks(...$callbacks);

        $this->extensionBuilder->build($operation, $routeInfo->extensionAttributes());

        return AvailableOperation::create(
            HttpMethod::from($routeInfo->method()),
            $operation,
        );
    }
}
