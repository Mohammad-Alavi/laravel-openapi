<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\RedirectResponse;

final class GitHubController extends Controller
{
    public function redirect(): RedirectResponse
    {
        return Socialite::driver('github')->scopes(['read:user', 'repo'])->redirect();
    }

    public function callback(): \Illuminate\Http\RedirectResponse
    {
        $githubUser = Socialite::driver('github')->user();

        $user = User::updateOrCreate(
            ['github_id' => (string) $githubUser->getId()],
            [
                'name' => $githubUser->getName() ?? $githubUser->getNickname(),
                'email' => $githubUser->getEmail(),
                'github_token' => $githubUser->token,
                'github_avatar' => $githubUser->getAvatar(),
            ],
        );

        Auth::login($user, remember: true);

        return redirect()->intended('/projects');
    }
}
