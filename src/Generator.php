<?php

namespace MohammadAlavi\LaravelOpenApi;

use Illuminate\Support\Arr;
use MohammadAlavi\LaravelOpenApi\Collectors\ComponentCollector;
use MohammadAlavi\LaravelOpenApi\Collectors\InfoBuilder;
use MohammadAlavi\LaravelOpenApi\Collectors\PathBuilder;
use MohammadAlavi\LaravelOpenApi\Collectors\ServerBuilder;
use MohammadAlavi\LaravelOpenApi\Collectors\TagBuilder;
use MohammadAlavi\LaravelOpenApi\Contracts\PathMiddleware;
use MohammadAlavi\LaravelOpenApi\oooas\Extensions\Extension;
use MohammadAlavi\LaravelOpenApi\oooas\Schema\Objects\OpenApi;

class Generator
{
    // TODO: Is this the right place for this constant?
    public const COLLECTION_DEFAULT = 'default';

    public function __construct(
        private readonly array $config,
        private readonly InfoBuilder $infoBuilder,
        private readonly ServerBuilder $serverBuilder,
        private readonly TagBuilder $tagBuilder,
        private readonly PathBuilder $pathBuilder,
        private readonly ComponentCollector $componentCollector,
    ) {
    }

    public function generate(string $collection = self::COLLECTION_DEFAULT): OpenApi
    {
        $info = $this->infoBuilder->build($this->getConfigFor('info', $collection));
        $servers = $this->serverBuilder->build($this->getConfigFor('servers', $collection));
        $extensions = $this->getConfigFor('extensions', $collection);
        $security = $this->getConfigFor('security', $collection);

        $middlewaresConfig = $this->getConfigFor('middlewares', $collection);
        $paths = $this->pathBuilder->build($collection, ...$this->getMiddlewares($middlewaresConfig));
        $components = $this->componentCollector->collect($collection, Arr::get($middlewaresConfig, 'components', []));
        $tags = $this->tagBuilder->build($this->getConfigFor('tags', $collection));

        $openApi = OpenApi::create()
            ->info($info)
            ->servers(...$servers)
            ->paths(...$paths)
            ->components($components)
            ->nestedSecurity($security)
            ->tags(...$tags);

        foreach ($extensions as $key => $value) {
            $openApi = $openApi->addExtension(Extension::create($key, $value));
        }

        return $openApi;
    }

    private function getConfigFor(string $configKey, string $collection): array
    {
        return Arr::get($this->config, 'collections.' . $collection . '.' . $configKey, []);
    }

    /** @return PathMiddleware[] */
    private function getMiddlewares(array $middlewaresConfig): array
    {
        return Arr::map(
            Arr::get($middlewaresConfig, 'paths', []),
            static fn (string $middlewares) => app($middlewares),
        );
    }
}
