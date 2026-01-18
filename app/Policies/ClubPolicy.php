<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Club;
use App\Models\User;
use App\Services\SubscriptionService;

final class ClubPolicy
{
    public function create(User $user): bool
    {
        app(SubscriptionService::class)->ensureCanCreateClub($user);

        return true;
    }

    public function view(User $user, Club $club): bool
    {
        return $user->clubs()
            ->where('club_id', $club->id)
            ->exists();
    }

    public function update(User $user, Club $club)
    {
        if (! $user->clubs->contains($club->id)) {
            return false;
        }

        if ($user->hasClubRole($club, 'owner')) {
            return true;
        }

        return $user->hasClubPermission($club, 'can_manage_club');
    }

    public function manageUsers(User $user, Club $club): bool
    {
        return $user->hasClubPermission($club, 'can_manage_users');
    }

    public function viewAnalytics(User $user): bool
    {
        return app(SubscriptionService::class)
            ->features($user)['analytics'];
    }
}
