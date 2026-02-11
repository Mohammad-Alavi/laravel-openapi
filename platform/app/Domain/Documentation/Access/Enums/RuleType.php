<?php

declare(strict_types=1);

namespace App\Domain\Documentation\Access\Enums;

enum RuleType: string
{
    case Tag = 'tag';
    case Path = 'path';
}
