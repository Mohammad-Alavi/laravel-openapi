<?php

declare(strict_types=1);

namespace App\Application\Documentation\Actions;

use App\Domain\Documentation\Access\Contracts\DocAccessLink;
use App\Domain\Documentation\Access\Events\AccessLinkCreated;
use App\Domain\Documentation\Access\Repositories\DocAccessLinkRepository;
use App\Domain\Documentation\Access\Repositories\DocRoleRepository;
use App\Domain\Documentation\Access\ValueObjects\PlainToken;

final class CreateAccessLink
{
    public function __construct(
        private readonly DocAccessLinkRepository $linkRepository,
        private readonly DocRoleRepository $roleRepository,
    ) {}

    /** @return array{token: PlainToken, link: DocAccessLink} */
    public function execute(int $projectId, int $roleId, string $name, ?string $expiresAt = null): array
    {
        $token = PlainToken::generate();
        $role = $this->roleRepository->findById($roleId);

        $link = $this->linkRepository->create([
            'project_id' => $projectId,
            'doc_role_id' => $roleId,
            'name' => $name,
            'token' => $token->hashed()->toString(),
            'expires_at' => $expiresAt,
        ]);

        AccessLinkCreated::dispatch(
            $projectId,
            $role->getName(),
            $expiresAt !== null,
        );

        return ['token' => $token, 'link' => $link];
    }
}
