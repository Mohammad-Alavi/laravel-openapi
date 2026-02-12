<?php

namespace App\Domain\Documentation\Access\ValueObjects;

use App\Domain\Documentation\Access\Contracts\DocAccessLink;

final readonly class CreateAccessLinkResult
{
    public function __construct(
        public PlainToken $token,
        public DocAccessLink $link,
    ) {}
}
