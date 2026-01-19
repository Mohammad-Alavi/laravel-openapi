<?php

namespace MohammadAlavi\Laragen;

use Illuminate\Support\Arr;
use Knuckles\Camel\Output\OutputEndpointData;
use Knuckles\Scribe\Writing\OpenApiSpecGenerators\BaseGenerator as ScribeOpenApiGenerator;

final class OpenAPIGenerator extends ScribeOpenApiGenerator
{
    public function pathItem(array $pathItem, array $groupedEndpoints, OutputEndpointData $endpoint): array
    {
        $route = Laragen::getRouteByUri($endpoint->uri);
        if (!is_null($route)) {
            $requestBodySchema = Laragen::extractRequestBodySchema($route)->compile();
            if (Arr::has($pathItem, 'requestBody.content')) {
                // Note: Only updates the first content type. Multiple encodings not yet supported.
                $encoding = array_key_first($pathItem['requestBody']['content']);
                $pathItem['requestBody']['content'][$encoding]['schema'] = $requestBodySchema;
            }
        }

        return parent::pathItem($pathItem, $groupedEndpoints, $endpoint);
    }
}
