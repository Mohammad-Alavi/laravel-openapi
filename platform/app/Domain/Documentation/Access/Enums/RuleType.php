<?php

namespace App\Domain\Documentation\Access\Enums;

enum RuleType: string
{
    case Tag = 'tag';
    case Path = 'path';
}
