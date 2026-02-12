<?php

namespace App\Application\DTOs;

use App\Models\User;
use Spatie\LaravelData\Data;

final class UserData extends Data
{
    public function __construct(
        public string $id,
        public string $name,
        public string $email,
        public string $github_id,
        public ?string $github_avatar,
    ) {}

    public static function fromModel(User $user): self
    {
        return new self(
            id: $user->ulid,
            name: $user->name,
            email: $user->email,
            github_id: $user->github_id,
            github_avatar: $user->github_avatar,
        );
    }
}
