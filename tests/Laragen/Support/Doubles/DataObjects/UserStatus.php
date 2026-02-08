<?php

namespace Tests\Laragen\Support\Doubles\DataObjects;

enum UserStatus: string
{
    case Active = 'active';
    case Inactive = 'inactive';
    case Banned = 'banned';
}
