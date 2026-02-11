<?php

declare(strict_types=1);

namespace App\Domain\Documentation\Access\Enums;

enum EndpointVisibility: string
{
    case Public = 'public';
    case Internal = 'internal';
    case Restricted = 'restricted';
    case Hidden = 'hidden';
}
