<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleAndUserSeeder extends Seeder
{
    /**
     * Demo password for every seeded account. Change immediately in any
     * non-local environment — these credentials are intentionally public
     * (documented in the project README) for MVP demo purposes only.
     */
    private const DEMO_PASSWORD = 'password';

    public function run(): void
    {
        $roles = ['customer', 'student', 'lab_staff', 'unit_head', 'director', 'admin'];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        $demoUsers = [
            ['name' => 'Somchai Customer', 'email' => 'customer@cesd.test', 'role' => 'customer', 'organization' => 'Lao Industry Co., Ltd.'],
            ['name' => 'Vanida Student', 'email' => 'student@cesd.test', 'role' => 'student', 'organization' => 'Faculty of Engineering, NUOL'],
            ['name' => 'Bounmy Labstaff', 'email' => 'labstaff@cesd.test', 'role' => 'lab_staff', 'organization' => 'CESD'],
            ['name' => 'Khamla Unithead', 'email' => 'unithead@cesd.test', 'role' => 'unit_head', 'organization' => 'CESD'],
            ['name' => 'Souliya Director', 'email' => 'director@cesd.test', 'role' => 'director', 'organization' => 'CESD'],
            ['name' => 'System Admin', 'email' => 'admin@cesd.test', 'role' => 'admin', 'organization' => 'CESD'],
        ];

        foreach ($demoUsers as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => self::DEMO_PASSWORD,
                    'organization' => $data['organization'],
                    'email_verified_at' => now(),
                ]
            );

            $user->syncRoles([$data['role']]);
        }
    }
}
