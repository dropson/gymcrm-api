<?php

declare(strict_types=1);

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'name' => $this->name,
            'email_verified' => $this->hasVerifiedEmail(),
            'subscription' => [
                'plan' => $this->subscription->plan,
                'is_active' => $this->subscription->is_active,
            ],
        ];
    }
}
