<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\ClubRole;
use App\Enums\ClubUserStatus;
use App\Models\Club;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

final class ClubFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->sentence(3);
        $address = [
            'country' => fake()->country(),
            'city' => fake()->city(),
            'street' => fake()->streetName(),
            'line' => fake()->buildingNumber(),
        ];

        $parts = [$name, $address['city'] ?? null, $address['street'] ?? null];
        $slugBase = Str::slug(implode(' ', array_filter($parts)));
        $slug = $slugBase;
        $i = 1;

        while (Club::where('slug', $slug)->exists()) {
            $slug = sprintf('%s-%d', $slugBase, $i);
            $i++;
        }

        return [
            'name' => $name,
            'slug' => $slug,
            'owner_id' => User::factory(),
            'logo_path' => 'clubs/logo_'.fake()->uuid.'.jpg',
            'phone' => fake()->phoneNumber(),
            'address' => $address,
            'working_hours' => [
                'mon' => ['08:00', '22:00'],
                'tue' => ['08:00', '22:00'],
                'wed' => ['08:00', '22:00'],
                'thu' => ['08:00', '22:00'],
                'fri' => ['08:00', '21:00'],
                'sat' => ['09:00', '18:00'],
                'sun' => null,
            ],
        ];
    }

    public function withOwner(User $user): self
    {
        return $this->state(fn (): array => [
            'owner_id' => $user->id,
        ])->afterCreating(function (Club $club) use ($user): void {
            $club->users()->attach($user->id, [
                'role' => ClubRole::Owner->value,
                'permissions' => null,
                'status' => ClubUserStatus::Active->value,
            ]);
        });
    }
}
