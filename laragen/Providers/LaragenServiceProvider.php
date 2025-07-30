<?php

namespace MohammadAlavi\Laragen\Providers;

use Illuminate\Support\ServiceProvider;
use MohammadAlavi\Laragen\Console\Generate;

final class LaragenServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->commands([
            Generate::class,
        ]);
    }
}
