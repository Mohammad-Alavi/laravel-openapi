<?php

namespace MohammadAlavi\LaravelOpenApi\Builders\Paths;

use MohammadAlavi\LaravelOpenApi\Builders\Paths\OperationBuilder\OperationBuilder;
use MohammadAlavi\LaravelOpenApi\Support\RouteInfo;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem\PathItem;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem\Support\AvailableOperation;

final readonly class PathItemBuilder
{
    public function __construct(
        private OperationBuilder $operationBuilder,
    ) {
    }

    public function build(RouteInfo ...$routeInfo): PathItem
    {
        $pathItem = PathItem::create();
        $operations = collect($routeInfo)
            ->map(
                function (RouteInfo $routeInfo): AvailableOperation {
                    return $this->operationBuilder->build($routeInfo);
                },
            )->all();

        return $pathItem->operations(...$operations);
    }
}
