<?php

namespace App\Domain\Documentation\Access\Enums;

enum DocVisibility: string
{
    case Public = 'public';
    case Private = 'private';
}
