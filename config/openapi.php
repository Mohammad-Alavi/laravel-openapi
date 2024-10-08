<?php

return [
    'collections' => [
        'default' => [
            'info' => [
                'title' => config('app.name'),
                'description' => null,
                'version' => '1.0.0',
                'contact' => [],
            ],

            'servers' => [
                // Servers should extend `MohammadAlavi\LaravelOpenApi\Factories\ServerFactory` class.
                // ExampleServer::class
            ],

            'tags' => [
                // Tags should extend `MohammadAlavi\LaravelOpenApi\Factories\TagFactory` class.
                // ExampleTag::class,
            ],

            'security' => [
                // Security schemes should extend `MohammadAlavi\LaravelOpenApi\Factories\Component\SecuritySchemeFactory` class.
                // BearerTokenSecurityScheme::class,
            ],

            // Non-standard attributes used by code/doc generation tools can be added here
            'extensions' => [
                // 'x-tagGroups' => [
                //     [
                //         'name' => 'General',
                //         'tags' => [
                //             'user',
                //         ],
                //     ],
                // ],
            ],

            // Route for exposing specification.
            // Leave uri null to disable.
            'route' => [
                'uri' => '/openapi',
                'middleware' => [],
            ],

            // Register custom middlewares for different objects.
            'middlewares' => [
                'paths' => [
                ],
                'components' => [
                ],
            ],
        ],
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
];
