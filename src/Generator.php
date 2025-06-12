<?php

namespace MohammadAlavi\LaravelOpenApi;

use Illuminate\Support\Arr;
use MohammadAlavi\LaravelOpenApi\Builders\Components\ComponentsBuilder;
use MohammadAlavi\LaravelOpenApi\Builders\InfoBuilder;
use MohammadAlavi\LaravelOpenApi\Builders\Paths\OperationBuilder\Builders\SecurityBuilder;
use MohammadAlavi\LaravelOpenApi\Builders\Paths\PathsBuilder;
use MohammadAlavi\LaravelOpenApi\Builders\ServerBuilder;
use MohammadAlavi\LaravelOpenApi\Builders\TagBuilder;
use MohammadAlavi\LaravelOpenApi\Services\RouteCollector;
use MohammadAlavi\ObjectOrientedOpenAPI\Extensions\Extension;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Components\Components;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\OpenAPI\OpenAPI;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\Security;

final readonly class Generator
{
    // TODO: Is this the right place for this constant?
    public const COLLECTION_DEFAULT = 'default';

    public function __construct(
        private InfoBuilder $infoBuilder,
        private ServerBuilder $serverBuilder,
        private TagBuilder $tagBuilder,
        private SecurityBuilder $securityBuilder,
        private PathsBuilder $pathsBuilder,
        private ComponentsBuilder $componentsBuilder,
        private RouteCollector $routeCollector,
    ) {
    }

    public function generate(string $collection = self::COLLECTION_DEFAULT): OpenAPI
    {
        $info = $this->infoBuilder->build($this->getConfigFor('info', $collection));
        $servers = $this->serverBuilder->build($this->getConfigFor('servers', $collection));
        $extensions = $this->getConfigFor('extensions', $collection);
        $globalSecurity = Arr::get(config('openapi'), sprintf('collections.%s.security', $collection));
        $security = $globalSecurity ? $this->securityBuilder->build($globalSecurity) : null;
        $paths = $this->pathsBuilder->build(
            $this->routeCollector->whereInCollection($collection),
        );
        $components = $this->componentsBuilder->build($collection);
        $tags = $this->tagBuilder->build($this->getConfigFor('tags', $collection));

        $openApi = OpenAPI::v311($info)
            ->servers(...$servers)
            ->paths($paths)
            ->tags(...$tags);
        $openApi = $components instanceof Components ? $openApi->components($components) : $openApi;

        $openApi = $security instanceof Security ? $openApi->security($security) : $openApi;
        foreach ($extensions as $key => $value) {
            $openApi = $openApi->addExtension(Extension::create($key, $value));
        }

        return $openApi;
    }

    private function getConfigFor(string $configKey, string $collection): array
    {
        return Arr::get(config('openapi'), 'collections.' . $collection . '.' . $configKey, []);
    }
}
