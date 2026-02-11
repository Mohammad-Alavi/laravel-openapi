<?php

namespace App\Providers;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RequestException::dontTruncate();
        Date::use(CarbonImmutable::class);
        Model::shouldBeStrict(! app()->isProduction());
        Model::automaticallyEagerLoadRelationships();
        DB::prohibitDestructiveCommands(app()->isProduction());
        Vite::prefetch();
        Password::defaults(fn () => Password::min(8)->letters()->mixedCase()->numbers()->uncompromised());

        if (app()->isProduction()) {
            URL::forceScheme('https');
        }
    }
}
