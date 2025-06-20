<?php

return [
    'collections' => [
        'default' => [
            // TODO: change this to use an InfoFactory class.
            'info' => [
                'title' => config('app.name'),
                'description' => null,
                'version' => '1.0.0',
                'contact' => [
                    'name' => null,
                    'email' => null,
                    'url' => null,
                ],
            ],

            'servers' => [
                // Servers should extend `MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\ServerFactory` class.
                // ExampleServer::class
            ],

            'tags' => [
                // Tags should extend `MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\TagFactory` class.
                // ExampleTag::class,
            ],

            // TODO: add an example for security factory.
            'security' => null,

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
