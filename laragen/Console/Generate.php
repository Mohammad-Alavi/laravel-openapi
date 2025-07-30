<?php

namespace MohammadAlavi\Laragen\Console;

use Knuckles\Camel\Camel;
use Knuckles\Camel\Output\OutputEndpointData;
use Knuckles\Scribe\Commands\GenerateDocumentation;
use Knuckles\Scribe\GroupedEndpoints\GroupedEndpointsFactory;
use Knuckles\Scribe\Matching\RouteMatcherInterface;
use Knuckles\Scribe\Writing\Writer;
use MohammadAlavi\Laragen\Laragen;
use Symfony\Component\Console\Attribute\AsCommand;

use function Laravel\Prompts\select;

#[AsCommand(
    name: 'laragen:generate',
    description: 'Generate OpenAPI specification for the application.',
)]
final class Generate extends GenerateDocumentation
{
    public function __construct()
    {
        parent::__construct();
        $this->setName('laragen:generate');
        $this->setDescription('Generate OpenAPI specification for the application.');
    }

    public function handle(RouteMatcherInterface $routeMatcher, GroupedEndpointsFactory $groupedEndpointsFactory): void
    {
        //        $this->bootstrap();
        //        $groupedEndpoints = $this->getGroupedEndpoints($groupedEndpointsFactory, $routeMatcher);
        //
        //        $configFileOrder = $this->docConfig->get('groups.order', []);
        //        $groupedEndpoints = Camel::prepareGroupedEndpointsForOutput($groupedEndpoints, $configFileOrder);
        //
        //        /** @var Writer $writer */
        //        $writer = app(Writer::class, ['config' => $this->docConfig, 'paths' => $this->paths]);
        //        $writer->writeDocs($groupedEndpoints);

        //        foreach ($groupedEndpoints as $group) {
        //            /** @var OutputEndpointData $endpoint */
        //            foreach ($group['endpoints'] as $endpoint) {
        //                $route = Laragen::getRouteByUri($endpoint->uri);
        //                if (!is_null($route)) {
        //                    Laragen::enrichWithExample(Laragen::getBodyParameters($route))->toArray();
        //                }
        //            }
        //        }

        $this->info('Generating OpenAPI specification...');
        $collection = select(
            'Select the collection to generate OpenAPI specification for:',
            array_keys(config('openapi.collections')),
            default: 'default',
        );

        Laragen::generate($collection)
            ->toJsonFile(
                'openapi',
                './.laragen',
                JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES,
            );
        $this->info('OpenAPI specification generated successfully for collection: ' . $collection);
    }

    private function getGroupedEndpoints(
        GroupedEndpointsFactory $groupedEndpointsFactory,
        RouteMatcherInterface $routeMatcher,
    ): array {
        $groupedEndpointsInstance = $groupedEndpointsFactory->make($this, $routeMatcher, $this->paths);
        $extractedEndpoints = $groupedEndpointsInstance->get();
        $userDefinedEndpoints = Camel::loadUserDefinedEndpoints(Camel::camelDir($this->paths));

        return $this->mergeUserDefinedEndpoints($extractedEndpoints, $userDefinedEndpoints);
    }
}
