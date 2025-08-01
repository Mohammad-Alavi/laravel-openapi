<?php

return [
    'laragen' => [
        'enabled' => env('LARAGEN_ENABLED', true),
        'autogen_request_body' => env('LARAGEN_AUTOGEN_REQUEST_BODY', true),
        'openapi_generator' => MohammadAlavi\Laragen\OpenAPIGenerator::class,
        'autogen_example' => env('LARAGEN_AUTOGEN_EXAMPLE', true),
    ],
];
