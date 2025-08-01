<?php

namespace MohammadAlavi\Laragen\Providers;

use Illuminate\Support\ServiceProvider;
use MohammadAlavi\Laragen\Console\Generate;
use MohammadAlavi\Laragen\ExampleGenerator\Date;
use MohammadAlavi\Laragen\ExampleGenerator\Email;
use MohammadAlavi\Laragen\ExampleGenerator\ExampleProvider;

final class LaragenServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/laragen.php',
            'laragen',
        );

        $this->commands([
            Generate::class,
        ]);
    }

    public function boot(): void
    {
        ExampleProvider::addExample(
            Date::class,
            Email::class,
        );

        $this->publishes([
            __DIR__ . '/../../config/laragen.php' => config_path('laragen.php'),
        ], 'laragen-config');
    }
}
