<?php

namespace MohammadAlavi\LaravelOpenApi\Builders;

use MohammadAlavi\LaravelOpenApi\Support\RouteInfo;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem\PathItem;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem\Support\AvailableOperation;

final readonly class PathItemBuilder
{
    public function __construct(
        private OperationBuilder $operationBuilder,
        private ServerBuilder $serverBuilder,
        private ParametersBuilder $parametersBuilder,
    ) {
    }

    public function build(RouteInfo ...$routeInfo): PathItem
    {
        $operations = collect($routeInfo)
            ->map(
                function (RouteInfo $routeInfo): AvailableOperation {
                    return $this->operationBuilder->build($routeInfo);
                },
            )->all();

        $pathItem = PathItem::create()->operations(...$operations);

        // TODO: This is stupid
        //  Refactor and reimplement this
        // https://spec.openapis.org/oas/latest.html#path-item-object
        $firstRouteInfo = $routeInfo[0];
        $attribute = $firstRouteInfo->pathItemAttribute();
        if (!is_null($attribute)) {
            if (!is_null($attribute->summary)) {
                $pathItem->summary($attribute->summary);
            }
            if (!is_null($attribute->description)) {
                $pathItem->description($attribute->description);
            }
            $pathItem->servers(...$this->serverBuilder->build(...$attribute->getServers()));
            $pathItem->parameters($this->parametersBuilder->build($firstRouteInfo));
        }

        return $pathItem;
    }
}
