<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\ClubRoleTemplate;
use Illuminate\Database\Seeder;

final class ClubRoleTemplateSeeder extends Seeder
{
    public function run(): void
    {
        ClubRoleTemplate::insert([
            [
                'role' => 'owner',
                'permissions' => json_encode([
                    'can_manage_club' => true,
                    'can_manage_users' => true,
                    'can_manage_trainers' => true,
                    'can_view_analytics' => true,
                    'can_manage_inventory' => true,
                ]),
            ],
            [
                'role' => 'manager',
                'permissions' => json_encode([
                    'can_manage_users' => true,
                    'can_manage_trainers' => true,
                    'can_view_analytics' => true,
                    'can_manage_inventory' => false,
                ]),
            ],
            [
                'role' => 'trainer',
                'permissions' => json_encode([
                    'can_manage_users' => true,
                    'can_view_analytics' => false,
                    'can_manage_trainers' => false,
                    'can_manage_inventory' => false,
                ]),
            ],
        ]);
    }
}
