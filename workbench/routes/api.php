<?php

use Illuminate\Support\Facades\Route;
use Workbench\App\Http\Controllers\CreateUserController;

Route::prefix('api')->group(
    static function (): void {
        Route::post('/users', [CreateUserController::class, '__invoke']);
        Route::delete('/users/{id}', [CreateUserController::class, '__invoke']);
        Route::get('/users/{id}/{lang?}', [CreateUserController::class, '__invoke']);

        /*
         * Healthcheck
         *
         * Check that the service is up. If everything is okay, you'll get a 200 OK response.
         *
         * Otherwise, the request will fail with a 400 error, and a response listing the failed services.
         *
         * @response 400 scenario="Service is unhealthy" {"status": "down", "services": {"database": "up", "redis": "down"}}
         * @responseField status The status of this API (`up` or `down`).
         * @responseField services Map of each downstream service and their status (`up` or `down`).
         */
        Route::get(
            '/healthcheck',
            static function (): array {
                return [
                    'status' => 'up',
                    'services' => [
                        'database' => 'up',
                        'redis' => 'up',
                    ],
                ];
            },
        );
    },
);
