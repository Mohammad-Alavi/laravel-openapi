<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;
use Inertia\Response;

final class ProfileController extends Controller
{
    public function show(Request $request): Response
    {
        return Inertia::render('Profile/Show', [
            'user' => $request->user()->only(['id', 'name', 'email', 'github_id', 'github_avatar']),
        ]);
    }

    public function sync(Request $request): RedirectResponse
    {
        $response = Http::withToken($request->user()->github_token)
            ->get('https://api.github.com/user');

        if ($response->failed()) {
            return redirect()->route('profile.show')
                ->with('error', 'Failed to sync GitHub profile. Please try re-authenticating.');
        }

        $data = $response->json();

        $request->user()->update([
            'name' => $data['name'] ?? $request->user()->name,
            'email' => $data['email'] ?? $request->user()->email,
            'github_avatar' => $data['avatar_url'] ?? $request->user()->github_avatar,
        ]);

        return redirect()->route('profile.show')
            ->with('success', 'GitHub profile synced successfully.');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $user = $request->user();

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $user->projects()->delete();
        $user->delete();

        return redirect('/');
    }
}
