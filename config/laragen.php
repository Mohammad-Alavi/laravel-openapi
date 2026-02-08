<?php

return [
    'enabled' => env('LARAGEN_ENABLED', true),
    'docs_path' => '.laragen/openapi.json',
    'route_discovery' => [
        'mode' => env('LARAGEN_ROUTE_DISCOVERY_MODE', 'attribute'), // 'auto' | 'attribute' | 'combined'
        'include' => ['api/*'],
        'exclude' => ['api/admin/*', 'api/telescope/*', 'api/horizon/*'],
    ],
    'autogen' => [
        'request_body' => env('LARAGEN_AUTOGEN_REQUEST_BODY', true),
        'example' => env('LARAGEN_AUTOGEN_EXAMPLE', true),
        'security' => env('LARAGEN_AUTOGEN_SECURITY', true),
        'path_parameters' => env('LARAGEN_AUTOGEN_PATH_PARAMETERS', true),
        'response' => env('LARAGEN_AUTOGEN_RESPONSE', true),
    ],
];
