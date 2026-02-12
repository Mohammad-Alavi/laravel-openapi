<?php

declare(strict_types=1);

use App\Models\User;

describe('Routing', function (): void {
    describe('redirects', function (): void {
        it('redirects root to login', function (): void {
            $response = $this->get('/');

            $response->assertRedirect('/login');
        });

        it('redirects dashboard to projects index for authenticated users', function (): void {
            $user = User::factory()->create();

            $response = $this->actingAs($user)->get('/dashboard');

            $response->assertRedirect(route('projects.index'));
        });

        it('redirects dashboard to login for guests', function (): void {
            $response = $this->get('/dashboard');

            $response->assertRedirect('/login');
        });
    });

    describe('guest middleware', function (): void {
        it('redirects authenticated users away from login page', function (): void {
            $user = User::factory()->create();

            $response = $this->actingAs($user)->get('/login');

            $response->assertRedirect();
        });

        it('shows login page to guests', function (): void {
            $response = $this->get('/login');

            $response->assertOk()
                ->assertInertia(fn ($page) => $page
                    ->component('Auth/Login')
                );
        });
    });

    describe('auth middleware', function (): void {
        it('redirects unauthenticated users to login for projects', function (): void {
            $this->get('/projects')->assertRedirect('/login');
        });

        it('redirects unauthenticated users to login for profile', function (): void {
            $this->get('/profile')->assertRedirect('/login');
        });

        it('redirects unauthenticated users to login for notifications', function (): void {
            $this->get('/notifications')->assertRedirect('/login');
        });

        it('redirects unauthenticated users to login for github repos', function (): void {
            $this->get('/github/repos')->assertRedirect('/login');
        });
    });
});
