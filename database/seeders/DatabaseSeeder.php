<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Club;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

final class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            ClubRoleTemplateSeeder::class,
            SubscriptionLimitSeeder::class,
        ]);

        $admin = User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'admin@example.com',
            'is_super_admin' => true,
        ]);

        Club::factory(3)->withOwner($admin)->create();
    }
}
