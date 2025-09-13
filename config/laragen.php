<?php

return [
    'enabled' => env('LARAGEN_ENABLED', true),
    'docs_path' => '.laragen/openapi.json',
    'autogen' => [
        'request_body' => env('LARAGEN_AUTOGEN_REQUEST_BODY', true),
        'example' => env('LARAGEN_AUTOGEN_EXAMPLE', true),
    ],
];
