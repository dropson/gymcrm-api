<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

final class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    private string $guard_name = 'api';

    public function subscription(): HasOne
    {
        return $this->hasOne(Subscription::class);
    }

    public function clubs(): BelongsToMany
    {
        return $this->belongsToMany(Club::class)
            ->withPivot(['role', 'permissions'])
            ->withTimestamps();
    }

    public function ownClubs(): HasMany
    {
        return $this->hasMany(Club::class, 'owner_id');
    }

    public function hasClubPermission(Club $club, string $permission): bool
    {
        $pivot = $this->clubs()
            ->where('club_id', $club->id)
            ->first()
            ?->pivot;

        if (! $pivot) {
            return false;
        }

        $permissions = $pivot->permissions
            ?? ClubRoleTemplate::where('role', $pivot->role)
                ->value('permissions');

        return (bool) data_get($permissions, $permission);
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
