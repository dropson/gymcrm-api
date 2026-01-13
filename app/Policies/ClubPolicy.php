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
