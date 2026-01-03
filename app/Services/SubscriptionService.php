<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\SubscriptionLimit;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

final class SubscriptionService
{
    public function limits(User $user): SubscriptionLimit
    {
        $plan = $user->subscription?->plan ?? 'free';

        return Cache::remember(
            'subscription_limits_{$plan}',
            now()->addHour(),
            fn () => SubscriptionLimit::where('plan', $plan)->firstOrFail()
        );
    }

    public function feature(User $user): array
    {
        $limits = $this->limits($user);

        return [
            'analytics' => $limits->analytics_access,
            'inventiry' => $limits->inventory_access,
        ];
    }

    public function canCreateClub(User $user): bool
    {
        $limits = $this->limits($user);

        if ($limits->max_clubs === null) {
            return true;
        }

        return $user->clubs()->count() < $limits->max_clubs;
    }

    public function upgrade(User $user, string $newPlan): void
    {
        $user->subscription()->update([
            'plan' => $newPlan,
        ]);

        Cache::forget('subscription_limits_'.$newPlan);
    }
}
