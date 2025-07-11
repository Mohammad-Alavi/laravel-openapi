<?php

namespace MohammadAlavi\LaravelOpenApi\Builders;

use Illuminate\Support\Collection;
use MohammadAlavi\LaravelOpenApi\Support\RouteInfo;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Paths\Fields\Path;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Paths\Paths;

final readonly class PathsBuilder
{
    public function __construct(
        private PathItemBuilder $pathItemBuilder,
    ) {
    }

    public function build(Collection $routeInfo): Paths
    {
        $paths = $routeInfo->groupBy(
            function (RouteInfo $routeInfo): string {
                return $routeInfo->uri();
            },
        )->map(
            function (Collection $routeInfo, string $url): Path {
                return Path::create(
                    $url,
                    $this->pathItemBuilder->build(...$routeInfo),
                );
            },
        )->values();

        return Paths::create(...$paths);
    }
}
