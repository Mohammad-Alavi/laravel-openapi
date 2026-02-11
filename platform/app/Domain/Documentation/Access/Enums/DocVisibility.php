<?php

declare(strict_types=1);

namespace App\Domain\Documentation\Access\Enums;

enum DocVisibility: string
{
    case Public = 'public';
    case Private = 'private';
}
