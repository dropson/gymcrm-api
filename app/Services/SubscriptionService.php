<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\SubscriptionLimitException;
use App\Models\Club;
use App\Models\SubscriptionLimit;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

final class SubscriptionService
{
    public function limits(User $user): SubscriptionLimit
    {
        $plan = $user->subscription?->plan ?? 'free';

        return Cache::remember(
            'subscription_limits_'.$plan,
            now()->addHour(),
            fn () => SubscriptionLimit::where('plan', $plan)->firstOrFail()
        );
    }

    public function features(User $user): array
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

    public function ensureCanCreateClub(User $user): void
    {
        $limits = $this->limits($user);

        if (
            $limits->max_clubs !== null &&
            $user->clubs()->count() >= $limits->max_clubs
        ) {
            throw new SubscriptionLimitException(
                resource: 'clubs',
                limit: $limits->max_clubs,
                plan: $limits->plan
            );
        }
    }

    public function ensureCanAddTrainer(User $user, Club $club): void
    {
        $limits = $this->limits($user);

        if ($limits->max_trainers === null) {
            return;
        }

        $count = $club->users()
            ->wherePivot('role', 'trainer')
            ->count();

        if ($count >= $limits->max_trainers) {
            throw new SubscriptionLimitException(
                resource: 'trainers',
                limit: $limits->max_trainers,
                plan: $limits->plan
            );
        }
    }

    public function ensureCanAddManager(User $user, Club $club): void
    {
        $limits = $this->limits($user);

        if ($limits->max_managers === null) {
            return;
        }

        $count = $club->users()
            ->wherePivot('role', 'manager')
            ->count();

        if ($count >= $limits->max_managers) {
            throw new SubscriptionLimitException(
                resource: 'managers',
                limit: $limits->max_managers,
                plan: $limits->plan
            );
        }
    }

    public function upgrade(User $user, string $newPlan): void
    {
        $user->subscription()->update([
            'plan' => $newPlan,
        ]);

        Cache::forget('subscription_limits_'.$newPlan);
    }
}
