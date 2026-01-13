<?php

declare(strict_types=1);

namespace App\Services;

use App\Http\Resources\V1\ClubSummaryResource;
use App\Models\Club;
use App\Models\User;

final class ClubContextService
{
    public function build(User $user, Club $club): array
    {
        return [
            'club' => $this->club($club),
            'permissions' => $this->permissions($user, $club),
            'features' => $this->features($user),
            'menu' => $this->menu($user, $club),
        ];
    }

    private function club(Club $club): ClubSummaryResource
    {
        return new ClubSummaryResource($club);
    }

    private function permissions(User $user, Club $club): array
    {
        return [
            'manage_users' => $user->hasClubPermission($club, 'can_manage_users'),
            'manage_trainers' => $user->hasClubPermission($club, 'can_manage_trainers'),
            'view_analytics' => $user->hasClubPermission($club, 'can_view_analytics'),
            'manage_inventory' => $user->hasClubPermission($club, 'can_manage_inventory'),
        ];
    }

    private function features(User $user): array
    {
        return app(SubscriptionService::class)->features($user);
    }

    private function menu(User $user, Club $club): array
    {
        $permissions = $this->permissions($user, $club);
        $features = $this->features($user);

        return [
            [
                'key' => 'overview',
                'enabled' => true,
            ],
            [
                'key' => 'trainers',
                'enabled' => $permissions['manage_trainers'],
            ],
            [
                'key' => 'users',
                'enabled' => $permissions['manage_users'],
            ],
            [
                'key' => 'analytics',
                'enabled' => $features['analytics'],
                'upgrade_required' => ! $features['analytics'],
            ],
            [
                'key' => 'inventory',
                'enabled' => $features['inventory'],
                'upgrade_required' => ! $features['inventory'],
            ],
        ];
    }
}
