<?php

use Illuminate\Support\Facades\Route;
use Workbench\App\Http\Controllers\CreateUserController;
use Workbench\App\Http\Controllers\UpdateUserController;

Route::prefix('api')->group(
    static function (): void {
        Route::post('/users', [CreateUserController::class, '__invoke']);
        Route::get('/users', [CreateUserController::class, '__invoke']);
        Route::patch('/users/{id}', [UpdateUserController::class, '__invoke']);
        Route::put(
            'users/{user}/posts/{slug}/comments/{id}/{not_in_method_sig}/{not_in_method_sig_opt?}/{noTypeParam}/{author_id?}',
            [UpdateUserController::class, 'methodWithParams'],
        );
        //        Route::delete('/users/{id}', [CreateUserController::class, '__invoke']);
        //        Route::get('/users/{id}/{lang?}', [CreateUserController::class, '__invoke']);

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
