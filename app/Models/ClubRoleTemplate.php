<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

final class ClubRoleTemplate extends Model
{
    protected $fillable = [
        'role',
        'permissions',
    ];

    protected $casts = [
        'permissions' => 'json',
    ];
}
