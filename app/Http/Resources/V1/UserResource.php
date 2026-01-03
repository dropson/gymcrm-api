<?php

declare(strict_types=1);

namespace App\Http\Resources\V1;

use App\Services\SubscriptionService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {

        $subscriptionServices = app(SubscriptionService::class);
        $limits = $subscriptionServices->limits($this->resource);

        return [
            'id' => $this->id,
            'email' => $this->email,
            'name' => $this->name,
            'email_verified' => $this->hasVerifiedEmail(),
            'subscription' => [
                'plan' => $this->subscription->plan,
                'is_active' => $this->subscription->is_active,
                'limits' => [
                    'max_clubs' => $limits->max_clubs,
                    'analytics' => $limits->analytics_access,
                    'inventory' => $limits->inventory_access,
                    'max_managers' => $limits->max_managers_per_club,
                    'max_trainers' => $limits->max_trainers_per_club,
                ],
            ],
        ];
    }
}
