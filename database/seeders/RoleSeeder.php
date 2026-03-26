<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['name' => 'Master Admin', 'slug' => 'master-admin'],
            ['name' => 'Admin', 'slug' => 'admin'],
            ['name' => 'Teacher', 'slug' => 'teacher'],
            ['name' => 'Student', 'slug' => 'student'],
            ['name' => 'Parent', 'slug' => 'parent'],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['slug' => $role['slug']], $role);
        }
    }
}
