<?php

declare(strict_types=1);

use App\Application\DTOs\UserData;
use App\Models\User;

describe(class_basename(UserData::class), function (): void {
    it('maps all fields from User model', function (): void {
        $user = User::factory()->create([
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'github_id' => '12345',
            'github_avatar' => 'https://avatars.githubusercontent.com/u/12345',
        ]);

        $dto = UserData::fromModel($user);

        expect($dto->id)->toBe($user->ulid)
            ->and($dto->name)->toBe('Jane Doe')
            ->and($dto->email)->toBe('jane@example.com')
            ->and($dto->github_id)->toBe('12345')
            ->and($dto->github_avatar)->toBe('https://avatars.githubusercontent.com/u/12345');
    });

    it('uses ULID as id, not integer primary key', function (): void {
        $user = User::factory()->create();

        $dto = UserData::fromModel($user);

        expect($dto->id)->toBe($user->ulid)
            ->and($dto->id)->not->toBe($user->id)
            ->and($dto->id)->toBeString();
    });

    it('handles null github_avatar', function (): void {
        $user = User::factory()->create(['github_avatar' => null]);

        $dto = UserData::fromModel($user);

        expect($dto->github_avatar)->toBeNull();
    });

    it('does not expose sensitive fields', function (): void {
        $user = User::factory()->create(['github_token' => 'secret-token']);

        $json = json_encode(UserData::fromModel($user));

        expect($json)->not->toContain('github_token')
            ->and($json)->not->toContain('remember_token')
            ->and($json)->not->toContain('secret-token');
    });
})->covers(UserData::class);
