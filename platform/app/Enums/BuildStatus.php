<?php

namespace App\Enums;

enum BuildStatus: string
{
    case Pending = 'pending';
    case Building = 'building';
    case Completed = 'completed';
    case Failed = 'failed';
}
