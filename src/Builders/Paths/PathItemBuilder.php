<?php

namespace MohammadAlavi\LaravelOpenApi\Builders\Paths;

use MohammadAlavi\LaravelOpenApi\Builders\Paths\OperationBuilder\OperationBuilder;
use MohammadAlavi\LaravelOpenApi\Objects\RouteInfo;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Operation;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem\PathItem;

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
                fn (RouteInfo $routeInfo): Operation => $this->operationBuilder->build($routeInfo),
            )->all();

        return $pathItem->operations(...$operations);
    }
}
