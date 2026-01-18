<?php

declare(strict_types=1);

namespace App\Enums;

enum ClubRole: string
{
    case Owner = 'owner';
    case Manager = 'manager';
    case Trainer = 'trainer';
}
