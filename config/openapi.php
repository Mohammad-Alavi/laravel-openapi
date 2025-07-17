<?php

use MohammadAlavi\LaravelOpenApi\Factories\ExampleFactory;

return [
    'collections' => [
        'default' => [
            'openapi' => ExampleFactory::class,
            // Route for exposing specification.
            // Leave uri null to disable.
            'route' => [
                'uri' => '/openapi',
                'middleware' => [],
            ],
            // Directories to use for locating OpenAPI object definitions.
            'locations' => [
                'callbacks' => [
                    app_path('OpenApi/Callbacks'),
                ],

                'request_bodies' => [
                    app_path('OpenApi/RequestBodies'),
                ],

                'responses' => [
                    app_path('OpenApi/Responses'),
                ],

                'schemas' => [
                    app_path('OpenApi/Schemas'),
                ],

                'security_schemes' => [
                    app_path('OpenApi/SecuritySchemes'),
                ],
            ],
        ],
    ],
];
