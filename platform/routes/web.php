<?php

declare(strict_types=1);

use App\Http\Controllers\Api\GitHubValidationController;
use App\Http\Controllers\Api\ProjectStatusController;
use App\Http\Controllers\Auth\GitHubController;
use App\Http\Controllers\Docs\DocAccessLinkController;
use App\Http\Controllers\Docs\DocRoleController;
use App\Http\Controllers\Docs\DocsController;
use App\Http\Controllers\Docs\DocSettingController;
use App\Http\Controllers\Docs\DocVisibilityRuleController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\RebuildController;
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

Route::get('/docs/{project:slug}', DocsController::class)->name('docs.show');

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
    Route::post('/projects/{project}/rebuild', RebuildController::class)->name('projects.rebuild');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::patch('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllRead');

    // Data classes handle authorization via authorize() for store/update routes
    Route::put('/projects/{project}/doc-settings', [DocSettingController::class, 'update'])->name('projects.doc-settings.update');
    Route::post('/projects/{project}/doc-roles', [DocRoleController::class, 'store'])->name('projects.doc-roles.store');
    Route::put('/projects/{project}/doc-roles/{docRole}', [DocRoleController::class, 'update'])->name('projects.doc-roles.update');
    Route::post('/projects/{project}/doc-rules', [DocVisibilityRuleController::class, 'store'])->name('projects.doc-rules.store');
    Route::put('/projects/{project}/doc-rules/{docRule}', [DocVisibilityRuleController::class, 'update'])->name('projects.doc-rules.update');
    Route::post('/projects/{project}/doc-links', [DocAccessLinkController::class, 'store'])->name('projects.doc-links.store');

    // Destroy routes use middleware since they have no Data class
    Route::delete('/projects/{project}/doc-roles/{docRole}', [DocRoleController::class, 'destroy'])->name('projects.doc-roles.destroy')->middleware('can:update,project');
    Route::delete('/projects/{project}/doc-rules/{docRule}', [DocVisibilityRuleController::class, 'destroy'])->name('projects.doc-rules.destroy')->middleware('can:update,project');
    Route::delete('/projects/{project}/doc-links/{docLink}', [DocAccessLinkController::class, 'destroy'])->name('projects.doc-links.destroy')->middleware('can:update,project');
});
