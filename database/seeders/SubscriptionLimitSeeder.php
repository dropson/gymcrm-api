<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\SubscriptionLimit;
use Illuminate\Database\Seeder;

final class SubscriptionLimitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SubscriptionLimit::insert([
            [
                'plan' => 'free',
                'max_clubs' => 1,
                'max_managers_per_club' => 1,
                'max_trainers_per_club' => 3,
                'analytics_access' => false,
                'inventory_access' => false,
            ],
            [
                'plan' => 'premium',
                'max_clubs' => 3,
                'max_managers_per_club' => 5,
                'max_trainers_per_club' => 10,
                'analytics_access' => true,
                'inventory_access' => false,
            ],
            [
                'plan' => 'unlimited',
                'max_clubs' => null,
                'max_managers_per_club' => null,
                'max_trainers_per_club' => null,
                'analytics_access' => true,
                'inventory_access' => true,
            ],
        ]);
    }
}
