<?php

namespace Tests\Laragen\Support\Doubles\Models;

enum StatusEnum: string
{
    case Active = 'active';
    case Inactive = 'inactive';
    case Pending = 'pending';
}
