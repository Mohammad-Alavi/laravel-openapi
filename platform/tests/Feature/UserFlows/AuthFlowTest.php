<?php

declare(strict_types=1);

use App\Models\User;

describe('Authentication Flow', function (): void {
    it('redirects guests through the full auth flow', function (): void {
        // Step 1: Visit any protected page as guest → redirect to login
        $this->get('/projects')
            ->assertRedirect('/login');

        // Step 2: Login page renders
        $this->get('/login')
            ->assertOk()
            ->assertInertia(fn ($page) => $page->component('Auth/Login'));

        // Step 3: GitHub redirect works
        $this->get('/auth/github/redirect')
            ->assertRedirect();
    });

    it('allows authenticated users to access all main pages', function (): void {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/projects')
            ->assertOk()
            ->assertInertia(fn ($page) => $page->component('Projects/Index'));

        $this->actingAs($user)->get('/projects/create')
            ->assertOk()
            ->assertInertia(fn ($page) => $page->component('Projects/Create'));

        $this->actingAs($user)->get('/profile')
            ->assertOk()
            ->assertInertia(fn ($page) => $page->component('Profile/Show'));

        $this->actingAs($user)->get('/notifications')
            ->assertOk()
            ->assertInertia(fn ($page) => $page->component('Notifications/Index'));
    });

    it('shares auth user data on every authenticated page', function (): void {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/projects')
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->has('auth.user')
                ->where('auth.user.id', $user->ulid)
                ->where('auth.user.name', $user->name)
                ->where('auth.user.email', $user->email)
                ->missing('auth.user.github_token')
                ->missing('auth.user.password')
            );
    });

    it('shares unread notification count on every page', function (): void {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/projects')
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->has('unreadNotificationsCount')
            );
    });

    it('logs out and redirects to root', function (): void {
        $user = User::factory()->create();

        $this->actingAs($user)->post('/logout')
            ->assertRedirect('/');

        $this->get('/projects')->assertRedirect('/login');
    });
});
