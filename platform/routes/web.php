<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\GitHubController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', function () {
    return Inertia::render('Auth/Login');
})->name('login')->middleware('guest');

Route::get('/auth/github/redirect', [GitHubController::class, 'redirect'])
    ->middleware('guest')
    ->name('github.redirect');

Route::get('/auth/github/callback', [GitHubController::class, 'callback'])
    ->middleware('guest')
    ->name('github.callback');

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/');
})->middleware('auth')->name('logout');

Route::middleware('auth')->group(function (): void {
    Route::get('/dashboard', fn () => redirect()->route('projects.index'))
        ->name('dashboard');

    Route::resource('projects', ProjectController::class);
});
