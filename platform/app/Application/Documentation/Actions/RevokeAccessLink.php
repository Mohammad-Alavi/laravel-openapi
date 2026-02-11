<?php

declare(strict_types=1);

namespace App\Application\Documentation\Actions;

use App\Domain\Documentation\Access\Entities\DocAccessLink;
use App\Domain\Documentation\Access\Events\AccessLinkRevoked;
use App\Domain\Documentation\Access\Repositories\DocAccessLinkRepository;

final class RevokeAccessLink
{
    public function __construct(
        private readonly DocAccessLinkRepository $repository,
    ) {}

    public function execute(int $linkId): void
    {
        $link = DocAccessLink::findOrFail($linkId);

        $this->repository->delete($linkId);

        AccessLinkRevoked::dispatch(
            $link->getProjectId(),
            $link->getName(),
        );
    }
}
