<?php

declare(strict_types=1);

use App\Http\Controllers\Api\GitHubValidationController;
use App\Http\Controllers\Api\ProjectStatusController;
use App\Http\Controllers\Auth\GitHubController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\Webhooks\GitHubWebhookController;
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

Route::post('/webhooks/github', GitHubWebhookController::class)->name('webhooks.github');

Route::middleware('auth')->group(function (): void {
    Route::get('/dashboard', fn () => redirect()->route('projects.index'))
        ->name('dashboard');

    Route::post('/github/validate-repo', GitHubValidationController::class)
        ->name('github.validate-repo');

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/profile/sync', [ProfileController::class, 'sync'])->name('profile.sync');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('projects', ProjectController::class);

    Route::get('/projects/{project}/status', ProjectStatusController::class)->name('projects.status');
});
